<?php

namespace App\Console\Commands;

use App\Mail\ElfArrivalMail;
use App\Mail\SantaLetterMail;
use App\Models\Letter;
use App\Support\SampleLetter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'letters:test-email {type : elf|santa} {email} {--variant=} {--letter= : Use a real letter id instead of sample data}';

    protected $description = 'Send a test Elf or Santa email to an address';

    public function handle(): int
    {
        $letter = $this->option('letter')
            ? Letter::findOrFail($this->option('letter'))
            : SampleLetter::make();

        if ($this->option('variant')) {
            $letter->santa_variant = (int) $this->option('variant');
        }

        $mail = match ($this->argument('type')) {
            'elf' => new ElfArrivalMail($letter),
            'santa' => new SantaLetterMail($letter),
            default => null,
        };
        if (! $mail) {
            $this->error('type must be elf or santa');

            return self::FAILURE;
        }

        Mail::to($this->argument('email'))->send($mail);
        $this->info('Sent '.$this->argument('type').' email to '.$this->argument('email'));

        return self::SUCCESS;
    }
}

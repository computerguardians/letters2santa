<?php

namespace App\Filament\Pages;

use App\Mail\ElfArrivalMail;
use App\Mail\SantaLetterMail;
use App\Support\SampleLetter;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Mail;

class EmailPreviews extends Page
{
    protected string $view = 'filament.pages.email-previews';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelopeOpen;

    protected static ?string $title = 'Email previews';

    protected static ?int $navigationSort = 8;

    public static function variantOptions(): array
    {
        $opts = ['elf' => 'Elf: letter arrived'];
        for ($i = 1; $i <= (int) config('letters.santa_variants'); $i++) {
            $opts["santa-$i"] = "Santa: variant $i";
        }

        return $opts;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendTest')->label('Send test email')->icon('heroicon-o-paper-airplane')
                ->schema([
                    Select::make('template')->options(self::variantOptions())->default('elf')->required(),
                    TextInput::make('email')->email()->required()->default(auth()->user()?->email),
                ])
                ->action(function (array $data) {
                    $letter = SampleLetter::make();
                    if ($data['template'] === 'elf') {
                        $mail = new ElfArrivalMail($letter);
                    } else {
                        $letter->santa_variant = (int) substr($data['template'], 6);
                        $mail = new SantaLetterMail($letter);
                    }
                    Mail::to($data['email'])->send($mail);
                    Notification::make()->title('Test email sent to '.$data['email'])->success()->send();
                }),
        ];
    }
}

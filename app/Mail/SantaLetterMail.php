<?php

namespace App\Mail;

use App\Models\Letter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SantaLetterMail extends Mailable
{
    use SerializesModels;

    public function __construct(public Letter $letter) {}

    public function variant(): int
    {
        $max = max(1, (int) config('letters.santa_variants'));
        $v = (int) ($this->letter->santa_variant ?: 1);

        return min(max($v, 1), $max);
    }

    public function envelope(): Envelope
    {
        $child = $this->letter->child_first_name;
        $subject = match ($this->variant()) {
            1 => "🎅 A letter from Santa for {$child}!",
            2 => "🎁 {$child}, Santa has written back to you",
            3 => "✨ Ho ho ho! Santa's letter for {$child} has arrived",
            default => "🛷 Merry Christmas {$child}, with love from Santa",
        };

        return new Envelope(
            from: new Address(config('letters.mail.santa_from'), config('letters.mail.santa_name')),
            replyTo: [new Address(config('letters.mail.reply_to'))],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.santa.variant-'.$this->variant(),
            with: $this->templateData(),
        );
    }

    public function attachments(): array
    {
        $data = $this->templateData() + ['variant' => $this->variant()];

        return [
            Attachment::fromData(
                fn () => Pdf::loadView('pdf.santa-letter', $data)->setPaper('a4')->output(),
                'Letter-from-Santa-for-'.preg_replace('/[^A-Za-z0-9\-]/', '', $this->letter->child_first_name).'.pdf'
            )->withMime('application/pdf'),
        ];
    }

    public function templateData(): array
    {
        $letter = $this->letter;

        return [
            'letter' => $letter,
            'child' => $letter->child_first_name,
            'town' => $letter->town,
            'country' => $letter->countryName(),
            'age' => $letter->child_age,
            'ageLine' => $letter->child_age ? "Being {$letter->child_age} is a wonderful age" : 'This is a wonderful age',
            'year' => \Carbon\Carbon::parse(config('letters.santa_date'))->year,
        ];
    }
}

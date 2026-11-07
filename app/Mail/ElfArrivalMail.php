<?php

namespace App\Mail;

use App\Models\Letter;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ElfArrivalMail extends Mailable
{
    use SerializesModels;

    public function __construct(public Letter $letter) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🎄 {$this->letter->child_first_name}'s letter has arrived at the North Pole!",
        );
    }

    public function content(): Content
    {
        $letter = $this->letter;
        $thumb = $letter->relationLoaded('images') ? $letter->images->first() : $letter->images()->first();

        return new Content(
            view: 'emails.elf',
            with: [
                'letter' => $letter,
                'parentName' => $letter->parent_name ?: 'there',
                'arrivedAt' => ($letter->paid_at ?? $letter->created_at)->timezone($letter->timezone)->format('g:i a \o\n l j F'),
                'thumbBytes' => $thumb?->thumbBytes(),
            ],
        );
    }
}

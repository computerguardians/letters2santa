<?php

namespace App\Mail;

use App\Models\Letter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LetterConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $letter;

    /**
     * Create a new message instance.
     */
    public function __construct(Letter $letter)
    {
        $this->letter = $letter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->getSubject(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.letter-confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get the email subject based on tier
     */
    private function getSubject(): string
    {
        if ($this->letter->tier === 'free') {
            return '🎅 Your FREE Christmas E-Book is Confirmed! - Letters2Santa';
        }

        return '🎅 Payment Confirmed - Christmas Magic Coming Soon! - Letters2Santa';
    }
}

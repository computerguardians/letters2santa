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
            subject: '🎅 Santa Has Received ' . $this->letter->child_name . '\'s Letter!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.letter-confirmation',
            text: 'emails.letter-confirmation-text',
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
}
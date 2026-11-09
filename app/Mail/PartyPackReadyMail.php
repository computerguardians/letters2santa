<?php

namespace App\Mail;

use App\Models\CreditPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent to the host once a credit purchase is paid: links to the poster, QR and party page. */
class PartyPackReadyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public CreditPurchase $purchase) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('letters.mail.elf_from'), config('letters.mail.elf_name')),
            replyTo: [new Address(config('letters.mail.reply_to'))],
            subject: '🎄 Your Letters2Santa party pack for '.$this->purchase->event->name.' is ready',
        );
    }

    public function content(): Content
    {
        $event = $this->purchase->event;

        return new Content(view: 'emails.party-pack-ready', with: [
            'purchase' => $this->purchase,
            'event' => $event,
            'business' => $event->business,
            'partyUrl' => $event->publicUrl(),
            'dashboardUrl' => route('host.events.show', $event),
            'posterUrl' => route('host.events.poster', $event),
            'screenUrl' => route('party.screen', $event->slug),
        ]);
    }
}

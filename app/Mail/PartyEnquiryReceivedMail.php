<?php

namespace App\Mail;

use App\Models\PartyEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Acknowledgement to the prospective host: we got your enquiry and will be in touch. */
class PartyEnquiryReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public PartyEnquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('letters.mail.elf_from'), config('letters.mail.elf_name')),
            replyTo: [new Address(config('letters.mail.reply_to'))],
            subject: '🎄 We got your Letters2Santa party pack enquiry',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.party-enquiry-received', with: [
            'enquiry' => $this->enquiry,
            'landingUrl' => route('party.landing'),
            'registerUrl' => route('host.register'),
            'supportEmail' => config('letters.business.support_email'),
        ]);
    }
}

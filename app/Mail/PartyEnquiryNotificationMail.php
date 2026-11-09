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

/** Internal notification to the support inbox when a host asks for a demo or setup help. */
class PartyEnquiryNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public PartyEnquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('letters.mail.elf_from'), 'Letters2Santa party packs'),
            replyTo: [new Address($this->enquiry->email, $this->enquiry->contact_name)],
            subject: '[Party pack enquiry] '.$this->enquiry->type->getLabel().' - '.$this->enquiry->organisation,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.party-enquiry-notification', with: [
            'enquiry' => $this->enquiry,
            'adminUrl' => url('/admin/party-enquiries/'.$this->enquiry->id),
        ]);
    }
}

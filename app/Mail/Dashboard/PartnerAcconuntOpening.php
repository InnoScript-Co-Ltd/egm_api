<?php

namespace App\Mail\Dashboard;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerAcconuntOpening extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    public $emailContent;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData, $emailContent)
    {
        $this->mailData = $mailData;
        $this->emailContent = $emailContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailContent['title']
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->emailContent['template'],
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

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RewardMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The email subject.
     *
     * @var string
     */
    public $subject;

    /**
     * The email content.
     *
     * @var string
     */
    public $emailContent;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $emailContent)
    {
        $this->subject = $subject;
        $this->emailContent = $emailContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.raw',
            with: [
                'content' => $this->emailContent,
            ],
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

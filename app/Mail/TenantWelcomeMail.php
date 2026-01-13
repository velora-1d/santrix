<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenantWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pesantrenName;
    public $loginUrl;
    public $email;
    public $password;
    public $trialEnd;

    /**
     * Create a new message instance.
     */
    public function __construct($pesantrenName, $loginUrl, $email, $password, $trialEnd)
    {
        $this->pesantrenName = $pesantrenName;
        $this->loginUrl = $loginUrl;
        $this->email = $email;
        $this->password = $password;
        $this->trialEnd = $trialEnd;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di Santrix - Detail Login Anda',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tenant.welcome',
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

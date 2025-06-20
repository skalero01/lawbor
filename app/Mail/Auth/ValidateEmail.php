<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ValidateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected $name,
        protected $code,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email validation: '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.validate_email',
            with: [
                'name' => $this->name,
                'code' => $this->code
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

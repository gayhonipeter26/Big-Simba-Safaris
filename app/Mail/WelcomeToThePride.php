<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeToThePride extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(public readonly mixed $newUser)
    {
        $this->user = $newUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to the Pride — Big Simba Safaris',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $logoPath = public_path('images/logo.png');
        $logoBase64 = file_exists($logoPath)
            ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath))
            : null;

        return new Content(
            view: 'emails.welcome',
            with: [
                'user' => $this->user,
                'logoBase64' => $logoBase64,
                'dashboardUrl' => route('dashboard'),
            ],
        );
    }
}

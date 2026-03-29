<?php

namespace App\Mail;

use App\Models\TourEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpeditionApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public TourEnquiry $tourEnquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Expedition Authorized: '.$this->tourEnquiry->tour->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.expeditions.approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

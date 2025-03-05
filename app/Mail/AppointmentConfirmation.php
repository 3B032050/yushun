<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $master_name;
    public $user_name;
    public $service_date;
    public $appointment_time;

    public function __construct($appointmentDetails)
    {
        $this->master_name = $appointmentDetails['master_name'];
        $this->user_name = $appointmentDetails['user_name'];
        $this->service_date = $appointmentDetails['service_date'];
        $this->appointment_time = $appointmentDetails['appointment_time'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '預約確認通知',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment_confirmation',
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
    public function build()
    {
        return $this->subject('預約確認通知')
            ->view('emails.appointment_confirmation');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CountryDataErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $countryCode;
    public $errors;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($countryCode,$errors)
    {
        $this->countryCode = $countryCode;
        $this->errors = $errors;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Country Data Error Notification',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.country_data_notification',
            with:[
                'countryCode'=>$this->countryCode,
                'errors'=>$this->errors
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}

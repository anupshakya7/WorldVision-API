<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CountryDataErrorNotication extends Mailable
{
    use Queueable, SerializesModels;

    public $errorMessage;
    public $context;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($errorMessage=[],$context=[])
    {
        $this->errorMessage = $errorMessage;
        $this->context = $context;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Country Data Error Notication',
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
                'errorMessage'=>$this->errorMessage,
                'context'=>$this->context
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

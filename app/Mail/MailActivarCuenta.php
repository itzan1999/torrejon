<?php

namespace App\Mail;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
// use Illuminate\Mail\Mailables\Content;
// use Illuminate\Mail\Mailables\Envelope;
// use Illuminate\Queue\SerializesModels;

class MailActivarCuenta extends Mailable
{
    // use Queueable, SerializesModels;

    public $token;
    public $nombreCompleto;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($nombreCompleto, $token, $email)
    { 
      $this->nombreCompleto = $nombreCompleto;
      $this->token = $token;
      $this->email = $email;
    }

    public function build()
    {
      $logoPath = public_path('resources/images/logo_main_black.png');

      return $this->to($this->email, $this->nombreCompleto)
          ->subject('Activar Cuenta')
          ->markdown('emails.activar-cuenta')
          ->withSymfonyMessage(function ($message) use ($logoPath) {
            $message->embedFromPath($logoPath, 'logo');
        });
    }
}
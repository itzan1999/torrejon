<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailEnviarFactura extends Mailable
{
  use Queueable, SerializesModels;

  public $pedido;

  /**
   * Create a new message instance.
   */
  public function __construct($pedido)
  {
    $this->pedido = $pedido;
  }

  public function build()
  {
    $logoPath = public_path('resources/images/logo_main_black.png');

    return $this->to($this->pedido->usuario->email, $this->pedido->usuario->cuenta->nombre . ' ' . $this->pedido->usuario->cuenta->apellidos)
      ->subject('Factura de tu Pedido #' . $this->pedido->codigo)
      ->markdown('emails.enviar-factura')
      ->withSymfonyMessage(function ($message) use ($logoPath) {
            $message->embedFromPath($logoPath, 'logo');    
      })
      ->attachData(
        $this->generarPDF(),
        'factura-' . $this->pedido->codigo . '.pdf',
        ['mime' => 'application/pdf']
      );
  }

  private function generarPDF()
  {
    return Pdf::loadView('pdfs.factura', ['pedido' => $this->pedido])->output();
  }
}
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservacionExitosa extends Mailable
{
    use Queueable, SerializesModels;

    public $reservacion;
    public $empleado;
    public $cliente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservacion, $cliente, $empleado)
    {
        $this->reservacion = $reservacion;
        $this->empleado = $empleado;
        $this->cliente = $cliente;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reservacion_exitosa');
    }
}

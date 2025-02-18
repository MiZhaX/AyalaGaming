<?php

namespace App\Email;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $user;

    public function __construct($payment, $user)
    {
        $this->payment = $payment;
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('ticket');
    }
}
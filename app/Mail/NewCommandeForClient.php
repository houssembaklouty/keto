<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCommandeForClient extends Mailable
{
    use Queueable, SerializesModels;

    public $ref;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ref)
    {
        $this->ref = $ref;
    }

    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('NV. COMMANDE')->markdown('emails.nv_commande_client');
    }
}
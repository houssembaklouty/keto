<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCommande extends Mailable
{
    use Queueable, SerializesModels;

    public $address;
    public $country;
    public $email_address;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $product;
    public $state;
    public $zip_code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($address, $country, $email_address, $first_name, $last_name, $phone_number, $product, $state, $zip_code)
    {
        $this->address = $address;
        $this->country = $country;
        $this->email_address = $email_address;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->phone_number = $phone_number;
        $this->product = $product;
        $this->state = $state;
        $this->zip_code = $zip_code;
    }

    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('NV. COMMANDE')
                    ->markdown('emails.nv_commande')
        ;
    }
}
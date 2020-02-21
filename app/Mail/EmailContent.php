<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailContent extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * Create for receiving data
     * from controller.
     * 
     * @var object $details
     */
    public $details;

    /**
     * Create a new message instance.
     *
     * @param object $dt
     * 
     * @return void
     */
    public function __construct($dt)
    {
        $this->details = $dt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email')
            ->subject('Verifikasi Email')
            ->with('data', $this->details);
    }
}

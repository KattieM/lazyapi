<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $username;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param String $name
     * @param String $username
     * @param String $password
     *
     * @return void
     */
    public function __construct($name, $username, $password)
    {
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to the Club | Lazy Brain!')->view('email')->with([
            'name' => $this->name,
            'password' => $this->password,
            'username' => $this->username
        ]);
    }
}

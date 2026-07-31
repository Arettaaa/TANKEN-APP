<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    // Deklarasikan variabel public agar bisa diakses langsung oleh file Blade
    public $otp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otpCode)
    {
        // Menangkap data dari AuthController
        $this->otp = $otpCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Kode Verifikasi Akun Tanken')
                    ->view('emails.otp'); // Mengarah ke resources/views/emails/otp.blade.php
    }
}
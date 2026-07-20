<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PembatalanPenugasanNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $sopir;
    public $keranjang;

    /**
     * Create a new message instance.
     */
    public function __construct($sopir, $keranjang)
    {
        $this->sopir = $sopir;
        $this->keranjang = $keranjang;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Pembatalan Penugasan Kerja - Sistem Penyewaan Truk')
            ->view('emails.pembatalan_notification')
            ->with([
                'sopir' => $this->sopir,
                'keranjang' => $this->keranjang,
            ]);
    }
}

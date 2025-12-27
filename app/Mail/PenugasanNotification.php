<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PenugasanNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $sopir;
    public $penyewaan;
    public $items; // collection of keranjangs for this sopir

    /**
     * Create a new message instance.
     */
    public function __construct($sopir, $penyewaan, $items)
    {
        $this->sopir = $sopir;
        $this->penyewaan = $penyewaan;
        $this->items = $items;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        \Log::info('PenugasanNotification::build() dipanggil untuk sopir: ' . ($this->sopir->nama ?? $this->sopir->name ?? 'Unknown'));
        
        return $this
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Penugasan Baru - Penyewaan #' . $this->penyewaan->id)
                    ->view('emails.penugasan_notification')
                    ->with([
                        'sopir' => $this->sopir,
                        'penyewaan' => $this->penyewaan,
                        'items' => $this->items,
                    ]);
    }
}

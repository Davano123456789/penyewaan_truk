<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        // Default URL Fonnte
        $this->baseUrl = env('FONNTE_BASE_URL', 'https://api.fonnte.com/send');
        // Token dari .env (atau hardcoded sementara sesuai request user jika env belum ada)
        $this->token = env('FONNTE_TOKEN', 'VcSvwnyCr1onEproduPW');
    }

    /**
     * Kirim pesan WhatsApp menggunakan Fonnte
     *
     * @param string $phoneNumber Nomor tujuan (format: 08xx atau 62xx)
     * @param string $message Isi pesan
     * @return bool
     */
    public function sendMessage($phoneNumber, $message)
    {
        if (empty($this->token)) {
            Log::warning('WhatsappService: Token Fonnte belum dikonfigurasi.');
            return false;
        }

        try {
            // Fonnte biasanya bisa menerima 08xx atau 62xx, tapi amannya kita bersihkan karakter non-digit
            // Dokumentasi Fonnte merekomendasikan format 08123... atau 628123...
            $target = preg_replace('/[^0-9]/', '', $phoneNumber);

            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->baseUrl, [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Optional, default to Indonesia logic if needed
            ]);

            // Cek respon
            if ($response->successful()) {
                $body = $response->json();
                // Fonnte biasanya return JSON {status: true, ...}
                if (isset($body['status']) && $body['status'] == true) {
                    Log::info("WhatsappService: Pesan terkirim via Fonnte ke $target");
                    return true;
                } else {
                    Log::error("WhatsappService: Fonnte return false. Response: " .json_encode($body));
                    return false;
                }
            } else {
                Log::error("WhatsappService: HTTP Error Fonnte. Status: " . $response->status() . " Body: " . $response->body());
                return false;
            }

        } catch (\Exception $e) {
            Log::error("WhatsappService: Exception saat kirim pesan Fonnte: " . $e->getMessage());
            return false;
        }
    }
}

<?php

namespace App\Services;

use App\Models\PawnTransaction;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send due date reminder notification
     */
    public function sendDueDateReminder(PawnTransaction $transaction, int $days)
    {
        $customer = $transaction->customer;
        
        // Kirim Email
        if ($customer->email) {
            try {
                Mail::send('emails.due-date-reminder', [
                    'customer' => $customer,
                    'transaction' => $transaction,
                    'days' => $days
                ], function ($message) use ($customer, $days) {
                    $message->to($customer->email, $customer->name)
                           ->subject("Pengingat Jatuh Tempo - {$days} Hari Lagi");
                });
                
                Log::info("Due date reminder email sent to {$customer->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send due date reminder email: " . $e->getMessage());
            }
        }

        // Kirim SMS (implementasi tergantung provider SMS yang digunakan)
        if ($customer->phone) {
            $this->sendSMS($customer->phone, $this->getDueDateReminderSMSMessage($transaction, $days));
        }

        // Kirim WhatsApp (implementasi tergantung API WhatsApp yang digunakan)
        if ($customer->phone) {
            $this->sendWhatsApp($customer->phone, $this->getDueDateReminderWhatsAppMessage($transaction, $days));
        }
    }

    /**
     * Send overdue reminder notification
     */
    public function sendOverdueReminder(PawnTransaction $transaction, int $daysOverdue)
    {
        $customer = $transaction->customer;
        
        // Kirim Email
        if ($customer->email) {
            try {
                Mail::send('emails.overdue-reminder', [
                    'customer' => $customer,
                    'transaction' => $transaction,
                    'daysOverdue' => $daysOverdue
                ], function ($message) use ($customer) {
                    $message->to($customer->email, $customer->name)
                           ->subject('Transaksi Jatuh Tempo - Segera Lakukan Pembayaran');
                });
                
                Log::info("Overdue reminder email sent to {$customer->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send overdue reminder email: " . $e->getMessage());
            }
        }

        // Kirim SMS
        if ($customer->phone) {
            $this->sendSMS($customer->phone, $this->getOverdueReminderSMSMessage($transaction, $daysOverdue));
        }

        // Kirim WhatsApp
        if ($customer->phone) {
            $this->sendWhatsApp($customer->phone, $this->getOverdueReminderWhatsAppMessage($transaction, $daysOverdue));
        }
    }

    /**
     * Send penalty notification
     */
    public function sendPenaltyNotification(PawnTransaction $transaction, float $penaltyAmount, int $penaltyDays)
    {
        $customer = $transaction->customer;
        
        // Kirim Email
        if ($customer->email) {
            try {
                Mail::send('emails.penalty-notification', [
                    'customer' => $customer,
                    'transaction' => $transaction,
                    'penaltyAmount' => $penaltyAmount,
                    'penaltyDays' => $penaltyDays
                ], function ($message) use ($customer) {
                    $message->to($customer->email, $customer->name)
                           ->subject('Denda Keterlambatan Diterapkan');
                });
                
                Log::info("Penalty notification email sent to {$customer->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send penalty notification email: " . $e->getMessage());
            }
        }

        // Kirim SMS
        if ($customer->phone) {
            $this->sendSMS($customer->phone, $this->getPenaltyNotificationSMSMessage($transaction, $penaltyAmount, $penaltyDays));
        }

        // Kirim WhatsApp
        if ($customer->phone) {
            $this->sendWhatsApp($customer->phone, $this->getPenaltyNotificationWhatsAppMessage($transaction, $penaltyAmount, $penaltyDays));
        }
    }

    /**
     * Send auction notification
     */
    public function sendAuctionNotification(PawnTransaction $transaction)
    {
        $customer = $transaction->customer;
        
        // Kirim Email
        if ($customer->email) {
            try {
                Mail::send('emails.auction-notification', [
                    'customer' => $customer,
                    'transaction' => $transaction
                ], function ($message) use ($customer) {
                    $message->to($customer->email, $customer->name)
                           ->subject('Pemberitahuan Lelang Barang Gadai');
                });
                
                Log::info("Auction notification email sent to {$customer->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send auction notification email: " . $e->getMessage());
            }
        }

        // Kirim SMS
        if ($customer->phone) {
            $this->sendSMS($customer->phone, $this->getAuctionNotificationSMSMessage($transaction));
        }

        // Kirim WhatsApp
        if ($customer->phone) {
            $this->sendWhatsApp($customer->phone, $this->getAuctionNotificationWhatsAppMessage($transaction));
        }
    }

    /**
     * Send SMS (implementasi tergantung provider SMS)
     */
    private function sendSMS(string $phone, string $message)
    {
        try {
            // Implementasi SMS gateway
            // Contoh menggunakan provider SMS seperti Twilio, Nexmo, dll
            
            // Untuk sementara, log saja
            Log::info("SMS sent to {$phone}: {$message}");
            
            // Contoh implementasi dengan Twilio:
            /*
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            $twilio->messages->create($phone, [
                'from' => config('services.twilio.from'),
                'body' => $message
            ]);
            */
            
        } catch (\Exception $e) {
            Log::error("Failed to send SMS to {$phone}: " . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp message (implementasi tergantung API WhatsApp)
     */
    private function sendWhatsApp(string $phone, string $message)
    {
        try {
            // Implementasi WhatsApp API
            // Contoh menggunakan WhatsApp Business API atau provider seperti Twilio
            
            // Untuk sementara, log saja
            Log::info("WhatsApp sent to {$phone}: {$message}");
            
            // Contoh implementasi dengan WhatsApp Business API:
            /*
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.whatsapp.token'),
                'Content-Type' => 'application/json',
            ])->post('https://graph.facebook.com/v17.0/' . config('services.whatsapp.phone_number_id') . '/messages', [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'text' => ['body' => $message]
            ]);
            */
            
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp to {$phone}: " . $e->getMessage());
        }
    }

    /**
     * Get due date reminder SMS message
     */
    private function getDueDateReminderSMSMessage(PawnTransaction $transaction, int $days): string
    {
        return "PEGADAIANKU: Halo {$transaction->customer->name}, transaksi {$transaction->transaction_code} akan jatuh tempo dalam {$days} hari pada " . $transaction->due_date->format('d/m/Y') . ". Sisa tagihan: Rp " . number_format($transaction->remaining_balance, 0, ',', '.') . ". Segera lakukan pembayaran.";
    }

    /**
     * Get due date reminder WhatsApp message
     */
    private function getDueDateReminderWhatsAppMessage(PawnTransaction $transaction, int $days): string
    {
        return "🔔 *PENGINGAT JATUH TEMPO*\n\nHalo {$transaction->customer->name},\n\nTransaksi gadai Anda:\n📋 Kode: {$transaction->transaction_code}\n📅 Jatuh tempo: " . $transaction->due_date->format('d/m/Y') . " ({$days} hari lagi)\n💰 Sisa tagihan: Rp " . number_format($transaction->remaining_balance, 0, ',', '.') . "\n\nSegera lakukan pembayaran untuk menghindari denda.\n\n*PEGADAIANKU*";
    }

    /**
     * Get overdue reminder SMS message
     */
    private function getOverdueReminderSMSMessage(PawnTransaction $transaction, int $daysOverdue): string
    {
        return "PEGADAIANKU: URGENT! {$transaction->customer->name}, transaksi {$transaction->transaction_code} telah jatuh tempo {$daysOverdue} hari. Sisa tagihan: Rp " . number_format($transaction->remaining_balance, 0, ',', '.') . ". Segera bayar untuk menghindari denda tambahan.";
    }

    /**
     * Get overdue reminder WhatsApp message
     */
    private function getOverdueReminderWhatsAppMessage(PawnTransaction $transaction, int $daysOverdue): string
    {
        return "🚨 *TRANSAKSI JATUH TEMPO*\n\nHalo {$transaction->customer->name},\n\nTransaksi gadai Anda:\n📋 Kode: {$transaction->transaction_code}\n⚠️ Telah jatuh tempo: {$daysOverdue} hari\n💰 Sisa tagihan: Rp " . number_format($transaction->remaining_balance, 0, ',', '.') . "\n\n*SEGERA LAKUKAN PEMBAYARAN* untuk menghindari denda tambahan.\n\n*PEGADAIANKU*";
    }

    /**
     * Get penalty notification SMS message
     */
    private function getPenaltyNotificationSMSMessage(PawnTransaction $transaction, float $penaltyAmount, int $penaltyDays): string
    {
        return "PEGADAIANKU: {$transaction->customer->name}, denda Rp " . number_format($penaltyAmount, 0, ',', '.') . " telah diterapkan pada transaksi {$transaction->transaction_code} karena terlambat {$penaltyDays} hari. Total tagihan: Rp " . number_format($transaction->remaining_balance + $penaltyAmount, 0, ',', '.');
    }

    /**
     * Get penalty notification WhatsApp message
     */
    private function getPenaltyNotificationWhatsAppMessage(PawnTransaction $transaction, float $penaltyAmount, int $penaltyDays): string
    {
        return "💸 *DENDA KETERLAMBATAN*\n\nHalo {$transaction->customer->name},\n\nDenda telah diterapkan:\n📋 Transaksi: {$transaction->transaction_code}\n⏰ Terlambat: {$penaltyDays} hari\n💰 Denda: Rp " . number_format($penaltyAmount, 0, ',', '.') . "\n💳 Total tagihan: Rp " . number_format($transaction->remaining_balance + $penaltyAmount, 0, ',', '.') . "\n\nSegera lakukan pembayaran.\n\n*PEGADAIANKU*";
    }

    /**
     * Get auction notification SMS message
     */
    private function getAuctionNotificationSMSMessage(PawnTransaction $transaction): string
    {
        return "PEGADAIANKU: PENTING! {$transaction->customer->name}, transaksi {$transaction->transaction_code} telah melewati batas waktu maksimal. Barang gadai akan diproses untuk lelang. Hubungi kami segera.";
    }

    /**
     * Get auction notification WhatsApp message
     */
    private function getAuctionNotificationWhatsAppMessage(PawnTransaction $transaction): string
    {
        return "🔨 *PEMBERITAHUAN LELANG*\n\nHalo {$transaction->customer->name},\n\nTransaksi gadai Anda:\n📋 Kode: {$transaction->transaction_code}\n\nTelah melewati batas waktu maksimal (120 hari). Barang gadai akan diproses untuk lelang.\n\n*HUBUNGI KAMI SEGERA* untuk penyelesaian.\n\n*PEGADAIANKU*";
    }
}
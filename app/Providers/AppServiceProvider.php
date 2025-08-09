<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set timezone untuk seluruh aplikasi
        date_default_timezone_set('Asia/Jakarta');
        
        // Set locale untuk Carbon (library tanggal Laravel)
        \Carbon\Carbon::setLocale('id');
        
        // Set default timezone untuk semua query database
        if (config('database.default') === 'mysql') {
            try {
                \DB::statement("SET time_zone = '+07:00'");
            } catch (\Exception $e) {
                // Ignore error jika database belum tersedia
            }
        }
        
        // Override Carbon format untuk Indonesia
        \Carbon\Carbon::macro('formatIndonesian', function ($format = 'd F Y') {
            // Pastikan instance Carbon menggunakan timezone Asia/Jakarta
            $carbon = $this->setTimezone('Asia/Jakarta');
            
            $months = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            
            $days = [
                0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
            ];
            
            $formatted = $carbon->format($format);
            
            // Replace bulan bahasa Inggris dengan Indonesia
            $englishMonths = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];
            
            // Replace hari bahasa Inggris dengan Indonesia
            $englishDays = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];
            
            $formatted = str_replace(array_keys($englishMonths), array_values($englishMonths), $formatted);
            $formatted = str_replace(array_keys($englishDays), array_values($englishDays), $formatted);
            
            return $formatted;
        });
        
        // Override diffForHumans untuk Indonesia
        \Carbon\Carbon::macro('diffForHumansIndonesian', function () {
            // Pastikan menggunakan timezone Asia/Jakarta
            $now = \Carbon\Carbon::now('Asia/Jakarta');
            $carbon = $this->setTimezone('Asia/Jakarta');
            
            $diff = $carbon->diffInSeconds($now);
            $isPast = $carbon->isPast();
            
            if ($diff < 60) {
                return $isPast ? 'beberapa detik yang lalu' : 'dalam beberapa detik';
            } elseif ($diff < 3600) {
                $minutes = floor($diff / 60);
                return $isPast ? $minutes . ' menit yang lalu' : 'dalam ' . $minutes . ' menit';
            } elseif ($diff < 86400) {
                $hours = floor($diff / 3600);
                return $isPast ? $hours . ' jam yang lalu' : 'dalam ' . $hours . ' jam';
            } elseif ($diff < 2592000) {
                $days = floor($diff / 86400);
                return $isPast ? $days . ' hari yang lalu' : 'dalam ' . $days . ' hari';
            } elseif ($diff < 31536000) {
                $months = floor($diff / 2592000);
                return $isPast ? $months . ' bulan yang lalu' : 'dalam ' . $months . ' bulan';
            } else {
                $years = floor($diff / 31536000);
                return $isPast ? $years . ' tahun yang lalu' : 'dalam ' . $years . ' tahun';
            }
        });
    }
}
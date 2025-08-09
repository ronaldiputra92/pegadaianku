<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format tanggal dalam bahasa Indonesia
     */
    public static function formatIndonesian($date, $format = 'd F Y')
    {
        if (!$date) return '-';
        
        $carbon = Carbon::parse($date)->setTimezone('Asia/Jakarta');
        
        // Array nama bulan dalam bahasa Indonesia
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        // Array nama hari dalam bahasa Indonesia
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
    }
    
    /**
     * Format tanggal dan waktu dalam bahasa Indonesia
     */
    public static function formatDateTimeIndonesian($date, $format = 'd F Y, H:i')
    {
        return self::formatIndonesian($date, $format) . ' WIB';
    }
    
    /**
     * Format tanggal singkat Indonesia
     */
    public static function formatShortIndonesian($date)
    {
        return self::formatIndonesian($date, 'd/m/Y');
    }
    
    /**
     * Format untuk diffForHumans dalam bahasa Indonesia
     */
    public static function diffForHumansIndonesian($date)
    {
        if (!$date) return '-';
        
        $carbon = Carbon::parse($date)->setTimezone('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        
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
    }
    
    /**
     * Get current date time in Jakarta timezone
     */
    public static function now()
    {
        return Carbon::now('Asia/Jakarta');
    }
    
    /**
     * Get today date in Jakarta timezone
     */
    public static function today()
    {
        return Carbon::today('Asia/Jakarta');
    }
    
    /**
     * Parse date with Jakarta timezone
     */
    public static function parse($date)
    {
        return Carbon::parse($date)->setTimezone('Asia/Jakarta');
    }
}
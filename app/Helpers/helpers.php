<?php

use App\Helpers\DateHelper;
use Carbon\Carbon;

if (!function_exists('formatDateIndonesian')) {
    /**
     * Format tanggal dalam bahasa Indonesia
     */
    function formatDateIndonesian($date, $format = 'd F Y')
    {
        return DateHelper::formatIndonesian($date, $format);
    }
}

if (!function_exists('formatDateTimeIndonesian')) {
    /**
     * Format tanggal dan waktu dalam bahasa Indonesia
     */
    function formatDateTimeIndonesian($date, $format = 'd F Y, H:i')
    {
        return DateHelper::formatDateTimeIndonesian($date, $format);
    }
}

if (!function_exists('formatShortDateIndonesian')) {
    /**
     * Format tanggal singkat Indonesia
     */
    function formatShortDateIndonesian($date)
    {
        return DateHelper::formatShortIndonesian($date);
    }
}

if (!function_exists('diffForHumansIndonesian')) {
    /**
     * Format untuk diffForHumans dalam bahasa Indonesia
     */
    function diffForHumansIndonesian($date)
    {
        return DateHelper::diffForHumansIndonesian($date);
    }
}

if (!function_exists('nowIndonesia')) {
    /**
     * Get current date time in Jakarta timezone
     */
    function nowIndonesia()
    {
        return Carbon::now('Asia/Jakarta');
    }
}

if (!function_exists('todayIndonesia')) {
    /**
     * Get today date in Jakarta timezone
     */
    function todayIndonesia()
    {
        return Carbon::today('Asia/Jakarta');
    }
}

if (!function_exists('parseDateIndonesia')) {
    /**
     * Parse date with Jakarta timezone
     */
    function parseDateIndonesia($date)
    {
        return Carbon::parse($date)->setTimezone('Asia/Jakarta');
    }
}
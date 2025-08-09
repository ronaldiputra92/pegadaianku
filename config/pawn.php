<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pawn Shop Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the pawn shop system
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Extension Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for pawn extension functionality
    |
    */
    'extension_admin_fee' => env('PAWN_EXTENSION_ADMIN_FEE', 50000), // Default Rp 50,000
    'max_extension_months' => env('PAWN_MAX_EXTENSION_MONTHS', 6), // Maximum 6 months
    'penalty_rate_per_day' => env('PAWN_PENALTY_RATE_PER_DAY', 0.001), // 0.1% per day

    /*
    |--------------------------------------------------------------------------
    | Interest Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for interest calculation
    |
    */
    'default_interest_rate' => env('PAWN_DEFAULT_INTEREST_RATE', 2.5), // 2.5% per month
    'max_interest_rate' => env('PAWN_MAX_INTEREST_RATE', 5.0), // Maximum 5% per month

    /*
    |--------------------------------------------------------------------------
    | Loan Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for loan calculation
    |
    */
    'default_ltv_ratio' => env('PAWN_DEFAULT_LTV_RATIO', 80), // 80% LTV ratio
    'max_ltv_ratio' => env('PAWN_MAX_LTV_RATIO', 90), // Maximum 90% LTV ratio
    'min_loan_amount' => env('PAWN_MIN_LOAN_AMOUNT', 100000), // Minimum Rp 100,000
    'max_loan_amount' => env('PAWN_MAX_LOAN_AMOUNT', 50000000), // Maximum Rp 50,000,000

    /*
    |--------------------------------------------------------------------------
    | Fee Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for various fees
    |
    */
    'default_admin_fee' => env('PAWN_DEFAULT_ADMIN_FEE', 25000), // Default Rp 25,000
    'default_insurance_fee' => env('PAWN_DEFAULT_INSURANCE_FEE', 15000), // Default Rp 15,000

    /*
    |--------------------------------------------------------------------------
    | Receipt Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for receipt generation
    |
    */
    'company_name' => env('PAWN_COMPANY_NAME', 'Pegadaian Digital'),
    'company_address' => env('PAWN_COMPANY_ADDRESS', 'Jl. Contoh No. 123, Kota Contoh'),
    'company_phone' => env('PAWN_COMPANY_PHONE', '(021) 1234-5678'),
    'company_email' => env('PAWN_COMPANY_EMAIL', 'info@pegadaianku.com'),
];
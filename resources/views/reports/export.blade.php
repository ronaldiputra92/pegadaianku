@extends('layouts.app')

@section('title', 'Export Data')

@section('page-title', 'Export Data')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-download mr-3 text-blue-600"></i>
                    Export Data
                </h1>
                <p class="mt-2 text-gray-600">Export berbagai jenis data laporan dalam format PDF atau CSV</p>
            </div>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-home mr-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('reports.index') }}" class="text-gray-500 hover:text-gray-700">Laporan</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-900 font-medium">Export Data</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Export Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Export Transaksi -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-handshake mr-3"></i>
                    Export Data Transaksi
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.export') }}" method="GET" target="_blank" class="space-y-4">
                    <input type="hidden" name="type" value="transactions">
                    
                    <div>
                        <label for="trans_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               id="trans_start_date" 
                               name="start_date" 
                               value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    
                    <div>
                        <label for="trans_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Akhir
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               id="trans_end_date" 
                               name="end_date" 
                               value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                    </div>
                    
                    <div>
                        <label for="trans_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Transaksi
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                id="trans_status" 
                                name="status">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="extended">Diperpanjang</option>
                            <option value="paid">Lunas</option>
                            <option value="overdue">Jatuh Tempo</option>
                            <option value="auction">Lelang</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="trans_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Format Export
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                id="trans_format" 
                                name="format">
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>
                        Export Transaksi
                    </button>
                </form>
            </div>
        </div>

        <!-- Export Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-money-bill-wave mr-3"></i>
                    Export Data Pembayaran
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.export') }}" method="GET" target="_blank" class="space-y-4">
                    <input type="hidden" name="type" value="payments">
                    
                    <div>
                        <label for="pay_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               id="pay_start_date" 
                               name="start_date" 
                               value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    
                    <div>
                        <label for="pay_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Akhir
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                               id="pay_end_date" 
                               name="end_date" 
                               value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                    </div>
                    
                    <div>
                        <label for="pay_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Pembayaran
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                id="pay_type" 
                                name="payment_type">
                            <option value="">Semua Jenis</option>
                            <option value="interest">Bunga</option>
                            <option value="partial">Sebagian</option>
                            <option value="full">Lunas</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="pay_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Format Export
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" 
                                id="pay_format" 
                                name="format">
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>
                        Export Pembayaran
                    </button>
                </form>
            </div>
        </div>

        <!-- Export Customer -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-users mr-3"></i>
                    Export Data Customer
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.export') }}" method="GET" target="_blank" class="space-y-4">
                    <input type="hidden" name="type" value="customers">
                    
                    <div>
                        <label for="cust_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Customer
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-colors" 
                                id="cust_status" 
                                name="status">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                            <option value="blocked">Diblokir</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="cust_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Format Export
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-colors" 
                                id="cust_format" 
                                name="format">
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    
                    <!-- Spacer untuk menyamakan tinggi dengan form lain -->
                    <div class="h-20"></div>
                    
                    <button type="submit" 
                            class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>
                        Export Customer
                    </button>
                </form>
            </div>
        </div>

        <!-- Export Laporan Keuangan -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-chart-line mr-3"></i>
                    Export Laporan Keuangan
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.export') }}" method="GET" target="_blank" class="space-y-4">
                    <input type="hidden" name="type" value="financial">
                    
                    <div>
                        <label for="fin_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors" 
                               id="fin_start_date" 
                               name="start_date" 
                               value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    
                    <div>
                        <label for="fin_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Akhir
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors" 
                               id="fin_end_date" 
                               name="end_date" 
                               value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                    </div>
                    
                    <div>
                        <label for="fin_format" class="block text-sm font-medium text-gray-700 mb-2">
                            Format Export
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors" 
                                id="fin_format" 
                                name="format">
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    
                    <!-- Spacer untuk menyamakan tinggi dengan form lain -->
                    <div class="h-5"></div>
                    
                    <button type="submit" 
                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>
                        Export Laporan Keuangan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-info-circle mr-3"></i>
                Informasi Export
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                        Format PDF
                    </h4>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Cocok untuk laporan formal
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Mudah dibaca dan dicetak
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Format yang konsisten
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-file-csv text-green-500 mr-2"></i>
                        Format CSV
                    </h4>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Cocok untuk analisis data
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Dapat dibuka di Excel/Spreadsheet
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Format universal dan ringan
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-lightbulb text-blue-500 mr-3 mt-1"></i>
                    <div>
                        <h5 class="font-semibold text-blue-900 mb-1">Tips Penggunaan:</h5>
                        <p class="text-blue-800 text-sm">
                            Gunakan filter tanggal dan status untuk mendapatkan data yang lebih spesifik sesuai kebutuhan laporan Anda. 
                            File akan didownload otomatis setelah Anda klik tombol export.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
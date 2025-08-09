@extends('layouts.app')

@section('title', 'Manajemen Pengingat')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Pengingat Jatuh Tempo</h1>
        <p class="text-gray-600 mt-2">Kelola pengingat otomatis dan manual untuk transaksi gadai</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-800">Akan Jatuh Tempo</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $stats['due_soon_count'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-800">Jatuh Tempo</p>
                    <p class="text-2xl font-bold text-red-900">{{ $stats['overdue_count'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-money-bill-wave text-orange-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-800">Total Denda</p>
                    <p class="text-lg font-bold text-orange-900">Rp {{ number_format($stats['total_penalty'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button onclick="showTab('due-soon')" id="tab-due-soon" class="tab-button active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Akan Jatuh Tempo ({{ $stats['due_soon_count'] }})
            </button>
            <button onclick="showTab('overdue')" id="tab-overdue" class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Jatuh Tempo ({{ $stats['overdue_count'] }})
            </button>
        </nav>
    </div>

    <!-- Due Soon Tab -->
    <div id="content-due-soon" class="tab-content">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Transaksi Akan Jatuh Tempo (7 hari ke depan)</h3>
                    @if($dueSoonTransactions->count() > 0)
                        <button onclick="sendBulkReminder('due_date', 'due-soon')" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Semua Reminder
                        </button>
                    @endif
                </div>
            </div>
            <div class="overflow-x-auto">
                @if($dueSoonTransactions->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all-due-soon" onchange="toggleSelectAll('due-soon')">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nasabah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Hari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Tagihan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($dueSoonTransactions as $transaction)
                                @php
                                    $daysLeft = \Carbon\Carbon::now()->diffInDays($transaction->due_date, false);
                                    $urgencyClass = $daysLeft <= 1 ? 'text-red-600' : ($daysLeft <= 3 ? 'text-orange-600' : 'text-yellow-600');
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="transaction-checkbox-due-soon" value="{{ $transaction->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->transaction_code }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->item_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->customer->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->due_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium {{ $urgencyClass }}">{{ $daysLeft }} hari</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($transaction->remaining_balance, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('reminders.send-manual', $transaction) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="type" value="due_date">
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-paper-plane mr-1"></i>Kirim Reminder
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-check-circle text-green-400 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Transaksi Akan Jatuh Tempo</h3>
                        <p class="text-gray-500">Semua transaksi dalam kondisi baik untuk 7 hari ke depan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Overdue Tab -->
    <div id="content-overdue" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Transaksi Jatuh Tempo</h3>
                    @if($overdueTransactions->count() > 0)
                        <button onclick="sendBulkReminder('overdue', 'overdue')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Kirim Semua Reminder
                        </button>
                    @endif
                </div>
            </div>
            <div class="overflow-x-auto">
                @if($overdueTransactions->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all-overdue" onchange="toggleSelectAll('overdue')">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nasabah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terlambat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tagihan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($overdueTransactions as $transaction)
                                @php
                                    $daysOverdue = \Carbon\Carbon::now()->diffInDays($transaction->due_date);
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="transaction-checkbox-overdue" value="{{ $transaction->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->transaction_code }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->item_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->customer->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->due_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-red-600">{{ $daysOverdue }} hari</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                        Rp {{ number_format($transaction->penalty_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        Rp {{ number_format($transaction->remaining_balance + ($transaction->penalty_amount ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <form action="{{ route('reminders.send-manual', $transaction) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="type" value="overdue">
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Reminder
                                                </button>
                                            </form>
                                            @if($transaction->penalty_amount > 0)
                                                <form action="{{ route('reminders.send-manual', $transaction) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="type" value="penalty">
                                                    <button type="submit" class="text-orange-600 hover:text-orange-900">
                                                        <i class="fas fa-money-bill-wave mr-1"></i>Denda
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-check-circle text-green-400 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Transaksi Jatuh Tempo</h3>
                        <p class="text-gray-500">Semua transaksi dalam kondisi baik.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
}

function toggleSelectAll(type) {
    const selectAllCheckbox = document.getElementById('select-all-' + type);
    const checkboxes = document.querySelectorAll('.transaction-checkbox-' + type);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function sendBulkReminder(reminderType, tabType) {
    const checkboxes = document.querySelectorAll('.transaction-checkbox-' + tabType + ':checked');
    
    if (checkboxes.length === 0) {
        alert('Pilih minimal satu transaksi untuk mengirim reminder.');
        return;
    }
    
    const transactionIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (confirm(`Kirim ${reminderType} reminder ke ${checkboxes.length} transaksi?`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("reminders.send-bulk") }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add type
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = reminderType;
        form.appendChild(typeInput);
        
        // Add transaction IDs
        transactionIds.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'transaction_ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    showTab('due-soon');
});
</script>

<style>
.tab-button.active {
    border-color: #3b82f6;
    color: #3b82f6;
}

.tab-button:not(.active) {
    border-color: transparent;
    color: #6b7280;
}

.tab-button:not(.active):hover {
    color: #374151;
    border-color: #d1d5db;
}
</style>
@endsection
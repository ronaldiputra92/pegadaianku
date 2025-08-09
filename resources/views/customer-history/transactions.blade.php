@extends('layouts.app')

@section('title', 'Transaksi Customer - ' . $customer->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Transaksi Customer</h1>
                    <p class="text-muted">Riwayat transaksi gadai untuk {{ $customer->name }}</p>
                </div>
                <div>
                    <a href="{{ route('customer-history.show', $customer) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-1">{{ $customer->name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-phone"></i> {{ $customer->phone }} | 
                                <i class="fas fa-id-card"></i> {{ $customer->id_number }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-primary fs-6">{{ $transactions->total() }} Transaksi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('customer-history.transactions', $customer) }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Selesai</option>
                                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Dari Tanggal</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">Sampai Tanggal</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-handshake"></i> Daftar Transaksi
                    </h5>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th>Jumlah Pinjaman</th>
                                        <th>Bunga (%)</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Status</th>
                                        <th>Petugas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <strong>{{ $transaction->transaction_code }}</strong>
                                        </td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $transaction->item_name }}</strong>
                                                @if($transaction->item_description)
                                                    <br>
                                                    <small class="text-muted">{{ $transaction->item_description }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>{{ $transaction->interest_rate }}%</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}
                                            @if(\Carbon\Carbon::parse($transaction->due_date)->isPast() && $transaction->status == 'active')
                                                <br><small class="text-danger">Terlambat</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaction->status == 'active')
                                                <span class="badge bg-primary">Aktif</span>
                                            @elseif($transaction->status == 'completed')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif($transaction->status == 'overdue')
                                                <span class="badge bg-warning">Terlambat</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->officer->name ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($transaction->status == 'active')
                                                    <a href="{{ route('payments.create', ['transaction' => $transaction->id]) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $transactions->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada transaksi</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['status', 'date_from', 'date_to']))
                                    Tidak ada transaksi yang sesuai dengan filter yang dipilih.
                                @else
                                    Customer ini belum memiliki transaksi gadai.
                                @endif
                            </p>
                            @if(request()->hasAny(['status', 'date_from', 'date_to']))
                                <a href="{{ route('customer-history.transactions', $customer) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-times"></i> Hapus Filter
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
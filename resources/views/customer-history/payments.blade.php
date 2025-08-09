@extends('layouts.app')

@section('title', 'Pembayaran Customer - ' . $customer->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Pembayaran Customer</h1>
                    <p class="text-muted">Riwayat pembayaran untuk {{ $customer->name }}</p>
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
                            <span class="badge bg-success fs-6">{{ $payments->total() }} Pembayaran</span>
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
                    <form method="GET" action="{{ route('customer-history.payments', $customer) }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="payment_type" class="form-label">Jenis Pembayaran</label>
                                <select name="payment_type" id="payment_type" class="form-select">
                                    <option value="">Semua Jenis</option>
                                    <option value="interest" {{ request('payment_type') == 'interest' ? 'selected' : '' }}>Bunga</option>
                                    <option value="principal" {{ request('payment_type') == 'principal' ? 'selected' : '' }}>Pokok</option>
                                    <option value="full" {{ request('payment_type') == 'full' ? 'selected' : '' }}>Pelunasan</option>
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

    <!-- Summary Cards -->
    @if($payments->count() > 0)
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Pembayaran</h6>
                            <h4 class="mb-0">Rp {{ number_format($payments->sum('amount'), 0, ',', '.') }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Pembayaran Bunga</h6>
                            <h4 class="mb-0">Rp {{ number_format($payments->where('payment_type', 'interest')->sum('amount'), 0, ',', '.') }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-percentage fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Pembayaran Pokok</h6>
                            <h4 class="mb-0">Rp {{ number_format($payments->where('payment_type', 'principal')->sum('amount'), 0, ',', '.') }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-coins fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Pelunasan</h6>
                            <h4 class="mb-0">Rp {{ number_format($payments->where('payment_type', 'full')->sum('amount'), 0, ',', '.') }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Payments Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-money-bill-wave"></i> Daftar Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal Bayar</th>
                                        <th>Kode Transaksi</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Petugas</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('transactions.show', $payment->pawnTransaction->id) }}" class="text-decoration-none">
                                                {{ $payment->pawnTransaction->transaction_code }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($payment->payment_type == 'interest')
                                                <span class="badge bg-info">Bunga</span>
                                            @elseif($payment->payment_type == 'principal')
                                                <span class="badge bg-success">Pokok</span>
                                            @elseif($payment->payment_type == 'full')
                                                <span class="badge bg-primary">Pelunasan</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($payment->payment_type) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @if($payment->payment_method == 'cash')
                                                <span class="badge bg-success">Tunai</span>
                                            @elseif($payment->payment_method == 'transfer')
                                                <span class="badge bg-primary">Transfer</span>
                                            @elseif($payment->payment_method == 'debit')
                                                <span class="badge bg-info">Debit</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->officer->name ?? '-' }}</td>
                                        <td>{{ $payment->notes ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada pembayaran</h5>
                            <p class="text-muted">
                                @if(request()->hasAny(['payment_type', 'date_from', 'date_to']))
                                    Tidak ada pembayaran yang sesuai dengan filter yang dipilih.
                                @else
                                    Customer ini belum melakukan pembayaran.
                                @endif
                            </p>
                            @if(request()->hasAny(['payment_type', 'date_from', 'date_to']))
                                <a href="{{ route('customer-history.payments', $customer) }}" class="btn btn-outline-primary">
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
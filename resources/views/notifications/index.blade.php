@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">Semua Notifikasi</h2>
                @if(auth()->user()->notifications()->unread()->count() > 0)
                    <button onclick="markAllAsRead()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-check-double mr-2"></i>
                        Tandai Semua Dibaca
                    </button>
                @endif
            </div>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="px-6 py-4 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <h3 class="text-sm font-medium text-gray-900 {{ !$notification->is_read ? 'font-semibold' : '' }}">
                                    {{ $notification->title }}
                                </h3>
                                @if(!$notification->is_read)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Baru
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $notification->message }}
                            </p>
                            <div class="mt-2 flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                                @if($notification->type)
                                    <span class="ml-3 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($notification->type) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex items-center space-x-2">
                            @if($notification->pawn_transaction_id)
                                <a href="{{ route('transactions.show', $notification->pawn_transaction_id) }}" 
                                   onclick="markAsRead({{ $notification->id }})"
                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat Transaksi
                                </a>
                            @endif
                            @if(!$notification->is_read)
                                <button onclick="markAsRead({{ $notification->id }})" 
                                        class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                                    <i class="fas fa-check mr-1"></i>
                                    Tandai Dibaca
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada notifikasi</h3>
                    <p class="text-gray-500">Anda belum memiliki notifikasi apapun.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        }).catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        }).catch(error => {
            console.error('Error marking all notifications as read:', error);
        });
    }
</script>
@endsection
@extends('index')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-bell me-2"></i>Notifikasi</h2>
                @if($notifications->where('is_read', false)->count() > 0)
                    <a href="{{ route('notifications.mark-all-read') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca
                    </a>
                @endif
            </div>

            @if($notifications->count() > 0)
                <div class="row">
                    @foreach($notifications as $notification)
                    <div class="col-12 mb-3">
                        <div class="card {{ $notification->is_read ? '' : 'border-primary' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-{{ $notification->type == 'success' ? 'success' : ($notification->type == 'danger' ? 'danger' : ($notification->type == 'warning' ? 'warning' : 'info')) }} me-2">
                                                @if($notification->type == 'success')
                                                    <i class="bi bi-check-circle me-1"></i>Berhasil
                                                @elseif($notification->type == 'danger')
                                                    <i class="bi bi-x-circle me-1"></i>Ditolak
                                                @elseif($notification->type == 'warning')
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Peringatan
                                                @else
                                                    <i class="bi bi-info-circle me-1"></i>Info
                                                @endif
                                            </span>
                                            @if(!$notification->is_read)
                                                <span class="badge bg-primary">Baru</span>
                                            @endif
                                        </div>
                                        <h6 class="card-title mb-2">{{ $notification->title }}</h6>
                                        <p class="card-text mb-2">{{ $notification->message }}</p>
                                        @if($notification->article)
                                            <a href="{{ route('articles.show', $notification->article->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>Lihat Artikel
                                            </a>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                                        @if(!$notification->is_read)
                                            <a href="{{ route('notifications.mark-read', $notification->id) }}" class="btn btn-outline-secondary btn-sm mt-2">
                                                <i class="bi bi-check me-1"></i>Tandai Dibaca
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Notifikasi</h4>
                    <p class="text-muted">Notifikasi akan muncul di sini ketika ada update terkait artikel Anda</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
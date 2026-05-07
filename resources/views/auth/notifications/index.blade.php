@extends('auth.theme.dashboard')

@section('title', __('pages.notifications'))

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ __('pages.notifications') }}</h6>
                    @if($notifications->where('read_at', null)->count() > 0)
                    <form method="POST" action="{{ route('notifications.markAllRead') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-sm bg-gradient-secondary text-white">
                            {{ __('pages.mark_all_read') }}
                        </button>
                    </form>
                    @endif
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    @if($notifications->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-bell-slash mb-3 opacity-50" viewBox="0 0 16 16">
                                <path d="M5.164 14H15c-.299-.199-.557-.553-.78-1C13.68 10.2 13 6.88 13 6v-.059l-1-1V6c0 .629-.134 2.197-.459 3.742-.323 1.55-.81 3.014-1.474 4.258H5.164zm-2.957 0H2l.261-.243C3.4 12.378 4 9.678 4 6c0-1.039.278-2.006.758-2.836L2.915 1.321A.5.5 0 0 1 3.621.615l11 11.5a.5.5 0 0 1-.736.677L12.367 10.8A7.4 7.4 0 0 1 12 12v.059l.82.86A1.5 1.5 0 0 1 11.72 15H4.28a1.5 1.5 0 0 1-1.1-2.519l.027-.028zM10 15a2 2 0 1 1-4 0h4z"/>
                            </svg>
                            <p>{{ __('pages.no_notifications') }}</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notif)
                            @php
                                $isUnread = is_null($notif->read_at);
                                $data = $notif->data;
                            @endphp
                            <div class="list-group-item px-4 py-3 {{ $isUnread ? 'bg-light' : '' }}"
                                 style="{{ $isUnread ? 'border-left: 3px solid #00A752;' : 'border-left: 3px solid transparent;' }}">
                                <div class="d-flex justify-content-between align-items-start">

                                    {{-- Left: unread dot + content --}}
                                    <div class="d-flex align-items-start gap-3" style="flex:1; min-width:0;">
                                        <span class="flex-shrink-0 mt-2"
                                              style="width:10px;height:10px;border-radius:50%;background:{{ $isUnread ? '#00A752' : '#ccc' }};display:inline-block;"></span>
                                        <div style="min-width:0;">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="text-sm font-weight-bold">{{ $data['subject'] ?? __('pages.notification') }}</span>
                                                @if($isUnread)
                                                    <span class="badge bg-gradient-success" style="font-size:9px;">{{ __('pages.unread') }}</span>
                                                @else
                                                    <span class="badge bg-gradient-secondary" style="font-size:9px;">{{ __('pages.read') }}</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-secondary mb-1" style="line-height:1.4;">
                                                {{ $data['message'] ?? '' }}
                                            </p>
                                            <span class="text-xs text-muted">
                                                <i class="fa fa-clock me-1"></i>
                                                {{ $notif->created_at->format('d/m/Y H:i') }}
                                                &mdash; {{ $notif->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Right: action buttons --}}
                                    <div class="d-flex align-items-center gap-2 ms-3 flex-shrink-0">
                                        @if(!empty($data['link']))
                                        <a href="{{ $isUnread ? route('notifications.markRead', $notif->id) : $data['link'] }}"
                                           class="btn btn-sm bg-gradient-dark text-white">
                                            {{ __('pages.view') }}
                                        </a>
                                        @elseif($isUnread)
                                        <a href="{{ route('notifications.markRead', $notif->id) }}"
                                           class="btn btn-sm bg-gradient-success text-white">
                                            {{ __('pages.mark_read') }}
                                        </a>
                                        @endif

                                        <form method="POST"
                                              action="{{ route('notifications.delete', $notif->id) }}"
                                              onsubmit="return confirm('{{ __('pages.confirm_delete_notif') }}')"
                                              class="mb-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm bg-gradient-danger text-white">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

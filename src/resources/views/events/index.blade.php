@extends('layouts.app')

@section('title', 'イベント一覧')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">イベント一覧</h1>
        <a href="{{ route('events.create') }}" class="btn btn-primary">イベントを作成</a>
    </div>

    @if ($events->isEmpty())
        <p class="text-muted">イベントはありません。</p>
    @else
        <div class="list-group mb-4">
            @foreach ($events as $event)
                <a href="{{ route('events.show', $event) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold">{{ $event->title }}</div>
                        <small class="text-muted">{{ $event->location }}</small>
                    </div>
                    <div class="text-end">
                        <div>{{ $event->starts_at->format('Y/m/d H:i') }}</div>
                        <small class="text-muted">残席 {{ $event->remainingSeats() }} / {{ $event->capacity }}</small>
                    </div>
                </a>
            @endforeach
        </div>

        {{ $events->links() }}
    @endif
@endsection

@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <a href="{{ route('events.index') }}" class="btn btn-link ps-0">&laquo; イベント一覧に戻る</a>

    @php($remaining = $event->remainingSeats())

    <div class="card">
        <div class="card-body">
            <h1 class="card-title h2">{{ $event->title }}</h1>
            <dl class="row mb-3">
                <dt class="col-sm-2">開催日時</dt>
                <dd class="col-sm-10">{{ $event->starts_at->format('Y/m/d H:i') }}</dd>
                <dt class="col-sm-2">場所</dt>
                <dd class="col-sm-10">{{ $event->location }}</dd>
                <dt class="col-sm-2">残席</dt>
                <dd class="col-sm-10">{{ $remaining }} / {{ $event->capacity }}</dd>
            </dl>
            <p class="card-text" style="white-space: pre-line;">{{ $event->description }}</p>
        </div>
    </div>

    <div class="mt-3">
        @if ($remaining > 0)
            <a href="{{ route('reservations.create', $event) }}" class="btn btn-primary">予約する</a>
        @else
            <button class="btn btn-secondary" disabled>満席です</button>
        @endif
    </div>
@endsection

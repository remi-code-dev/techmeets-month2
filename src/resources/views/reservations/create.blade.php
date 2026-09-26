@extends('layouts.app')

@section('title', '予約作成')

@section('content')
    <h1 class="mb-1">予約作成</h1>
    <p class="text-muted mb-4">{{ $event->title }}（残席 {{ $event->remainingSeats() }}）</p>

    <form action="{{ route('reservations.store', $event) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">名前</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">メールアドレス</label>
            <input type="email" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="guests" class="form-label">人数</label>
            <input type="number" name="guests" id="guests" min="1"
                   class="form-control @error('guests') is-invalid @enderror"
                   value="{{ old('guests', 1) }}">
            @error('guests')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="reserved_at" class="form-label">予約日時</label>
            <input type="datetime-local" name="reserved_at" id="reserved_at"
                   class="form-control @error('reserved_at') is-invalid @enderror"
                   value="{{ old('reserved_at') }}">
            @error('reserved_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">予約する</button>
        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">キャンセル</a>
    </form>
@endsection

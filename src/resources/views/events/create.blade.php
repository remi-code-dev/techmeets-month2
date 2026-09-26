@extends('layouts.app')

@section('title', 'イベント作成')

@section('content')
    <h1 class="mb-4">イベント作成</h1>

    <form action="{{ route('events.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">イベント名</label>
            <input type="text" name="title" id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">場所</label>
            <input type="text" name="location" id="location"
                   class="form-control @error('location') is-invalid @enderror"
                   value="{{ old('location') }}">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="starts_at" class="form-label">開催日時</label>
            <input type="datetime-local" name="starts_at" id="starts_at"
                   class="form-control @error('starts_at') is-invalid @enderror"
                   value="{{ old('starts_at') }}">
            @error('starts_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="capacity" class="form-label">定員</label>
            <input type="number" name="capacity" id="capacity" min="1"
                   class="form-control @error('capacity') is-invalid @enderror"
                   value="{{ old('capacity') }}">
            @error('capacity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">内容</label>
            <textarea name="description" id="description" rows="8"
                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">作成する</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">キャンセル</a>
    </form>
@endsection

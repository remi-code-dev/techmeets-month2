@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <a href="{{ route('posts.index') }}" class="btn btn-link ps-0">&laquo; 一覧に戻る</a>

    <div class="card">
        <div class="card-body">
            <span class="badge bg-secondary mb-2">{{ $post->category->name }}</span>
            <h1 class="card-title h2">{{ $post->title }}</h1>
            <p class="text-muted small">
                投稿日: {{ $post->created_at->format('Y/m/d H:i') }}
                @if ($post->created_at->ne($post->updated_at))
                    （更新日: {{ $post->updated_at->format('Y/m/d H:i') }}）
                @endif
            </p>
            <p class="card-text" style="white-space: pre-line;">{{ $post->content }}</p>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">編集</a>

        <form action="{{ route('posts.destroy', $post) }}" method="POST"
              onsubmit="return confirm('本当に削除しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">削除</button>
        </form>
    </div>
@endsection

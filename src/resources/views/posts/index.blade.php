@extends('layouts.app')

@section('title', '投稿一覧')

@section('content')
    <h1 class="mb-4">投稿一覧</h1>

    @if ($posts->isEmpty())
        <p class="text-muted">まだ投稿がありません。</p>
    @else
        <div class="list-group mb-4">
            @foreach ($posts as $post)
                <a href="{{ route('posts.show', $post) }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold">{{ $post->title }}</div>
                        <span class="badge bg-secondary">{{ $post->category->name }}</span>
                    </div>
                    <small class="text-muted">{{ $post->created_at->format('Y/m/d H:i') }}</small>
                </a>
            @endforeach
        </div>

        {{ $posts->links() }}
    @endif
@endsection

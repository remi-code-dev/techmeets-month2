@extends('layouts.app')

@section('title', '投稿編集')

@section('content')
    <h1 class="mb-4">投稿編集</h1>

    <form action="{{ route('posts.update', $post) }}" method="POST">
        @method('PUT')
        @include('posts._form', ['post' => $post, 'submitLabel' => '更新する'])
    </form>
@endsection

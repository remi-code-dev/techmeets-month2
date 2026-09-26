@extends('layouts.app')

@section('title', '新規投稿')

@section('content')
    <h1 class="mb-4">新規投稿</h1>

    <form action="{{ route('posts.store') }}" method="POST">
        @include('posts._form', ['post' => null, 'submitLabel' => '投稿する'])
    </form>
@endsection

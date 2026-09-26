<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ブログ')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('posts.index') }}">My Blog</a>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-light btn-sm" href="{{ route('events.index') }}">イベント</a>
                <a class="btn btn-outline-light btn-sm" href="{{ route('reservations.index') }}">予約一覧</a>
                <a class="btn btn-outline-light btn-sm" href="{{ route('posts.create') }}">新規投稿</a>
            </div>
        </div>
    </nav>

    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

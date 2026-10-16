<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

// routes/api.php のルートには自動で /api プレフィックスが付く
// GET /api/posts      → 投稿一覧
// GET /api/posts/{id} → 投稿詳細
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

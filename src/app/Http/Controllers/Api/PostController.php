<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        // with('user') で投稿者をまとめて取得（N+1 対策）
        $posts = Post::with('user')->latest()->get();

        // collection() で { "data": [ ... ] } の形に包んで返す
        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);

        return new PostResource($post);
    }
}

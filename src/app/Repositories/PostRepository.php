<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    // 全件取得（最新順・ページネーション付き）
    public function getAll()
    {
        return Post::latest()->paginate(10);
    }

    // IDで1件取得
    public function findById(int $id)
    {
        return Post::findOrFail($id);
    }

    // 公開済み投稿だけ取得
    public function getPublished()
    {
        return Post::where('status', 'published')
            ->latest()
            ->paginate(10);
    }

    // 特定ユーザーの投稿を取得
    public function getByUser(int $userId)
    {
        return Post::where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function update(Post $post, array $data)
    {
        $post->update($data);
        return $post;
    }

    public function delete(Post $post)
    {
        return $post->delete();
    }
}
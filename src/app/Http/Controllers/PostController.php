<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * 投稿一覧表示（ページネーション付き）
     */
    public function index(): View
    {
        $posts = Post::with('category')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * 投稿作成フォームの表示
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('posts.create', compact('categories'));
    }

    /**
     * 投稿の保存
     */
    public function store(PostRequest $request): RedirectResponse
    {
        // 投稿者はリクエストではなくログインユーザーから設定する
        $post = $request->user()->posts()->create($request->validated());

        return redirect()
            ->route('posts.show', $post)
            ->with('status', '投稿を作成しました。');
    }

    /**
     * 投稿詳細表示
     */
    public function show(Post $post): View
    {
        $post->load(['category', 'user']);

        return view('posts.show', compact('post'));
    }

    /**
     * 投稿編集フォームの表示
     */
    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        $categories = Category::orderBy('name')->get();

        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * 投稿の更新
     */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $post->update($request->validated());

        return redirect()
            ->route('posts.show', $post)
            ->with('status', '投稿を更新しました。');
    }

    /**
     * 投稿の削除
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('status', '投稿を削除しました。');
    }
}
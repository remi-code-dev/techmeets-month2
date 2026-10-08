<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService,
        private PostRepository $postRepository
    ) {}

    public function index()
    {
        $posts = $this->postRepository->getPublished();
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:200',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = auth()->id();

        // コントローラーは「受け取って渡す」だけ。処理の詳細はServiceに任せる
        $post = $this->postService->createPost($validated);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', '投稿を作成しました');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:200',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $post = $this->postService->updatePost($id, $validated);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', '投稿を更新しました');
    }
}
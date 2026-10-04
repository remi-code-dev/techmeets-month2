<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = Comment::with('user')->latest()->paginate(20);

        return view('comments.index', compact('comments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        // user_id はリクエストから受け取らず、ログインユーザーに紐づけて保存する
        $request->user()->comments()->create($validated);

        return redirect()->route('comments.index')
            ->with('status', '投稿しました。');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return redirect()->route('comments.index')
            ->with('status', '削除しました。');
    }
}

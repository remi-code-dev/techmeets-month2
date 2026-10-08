<?php

namespace App\Services;

use App\Repositories\PostRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PostService
{
    // コンストラクタインジェクション（DIパターン、次のSection 4で詳しく説明）
    // 「private PostRepository $postRepository」はPHP 8のコンストラクタプロモーションという書き方
    // 従来の書き方と同じ意味：
    //   private PostRepository $postRepository;
    //   public function __construct(PostRepository $postRepository) {
    //       $this->postRepository = $postRepository;
    //   }
    public function __construct(
        private PostRepository $postRepository
    ) {}

    public function createPost(array $data)
    {
        // DB::transaction(): この中の処理が全て成功すれば確定、途中でエラーが起きたら全て取り消す
        return DB::transaction(function () use ($data) {
            // 1. 投稿作成
            $post = $this->postRepository->create($data);

            // 2. 画像処理
            if (isset($data['image'])) {
                $this->processImage($post, $data['image']);
            }

            // 3. 通知送信
            $this->sendNotifications($post);

            // 4. ログ記録
            Log::info('Post created', ['post_id' => $post->id]);

            return $post;
        });
    }

    public function updatePost(int $id, array $data)
    {
        $post = $this->postRepository->findById($id);

        return DB::transaction(function () use ($post, $data) {
            $updated = $this->postRepository->update($post, $data);

            if (isset($data['image'])) {
                $this->processImage($updated, $data['image']);
            }

            Log::info('Post updated', ['post_id' => $updated->id]);

            return $updated;
        });
    }

    // privateメソッド: このクラス内からしか呼べない（外部に公開する必要がない処理）
    private function processImage($post, $image)
    {
        $path = $image->store('posts');
        $post->update(['image_path' => $path]);
    }

    private function sendNotifications($post)
    {
        Mail::to($post->user)->send(new PostCreated($post));
    }
}
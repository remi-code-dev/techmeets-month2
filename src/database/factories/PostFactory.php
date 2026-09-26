<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * @var array<int, string>
     */
    private const TITLES = [
        'Laravelを始めてみました',
        'Docker環境の構築メモ',
        '今日の学習ログ',
        'MVCパターンについて整理する',
        'Bladeのレイアウト継承を試す',
        'マイグレーションの使い方',
        'Eloquentのリレーション入門',
        'バリデーションで入力チェック',
        'ページネーションを実装した',
        '週末に読んだ技術書の感想',
        'デバッグで詰まったところ',
        'Gitのブランチ運用を見直す',
    ];

    /**
     * @var array<int, string>
     */
    private const SENTENCES = [
        '今日は新しいことに挑戦してみました。',
        '最初は分からないことだらけでしたが、少しずつ理解できてきました。',
        '公式ドキュメントを読みながら、実際に手を動かして確認しました。',
        'エラーが出たときは、メッセージをよく読むことが大切だと感じました。',
        'うまく動いたときの達成感は格別です。',
        '次回はもう少し発展的な内容に取り組む予定です。',
        '忘れないように、ここにメモを残しておきます。',
        '同じところで悩んでいる人の参考になればうれしいです。',
        '小さな改善を積み重ねることが上達への近道だと思います。',
        'コードを整理すると、全体の見通しがよくなりました。',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paragraphs = collect(range(1, 3))
            ->map(fn () => implode('', fake()->randomElements(self::SENTENCES, 4)))
            ->implode("\n\n");

        return [
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
            'title' => fake()->randomElement(self::TITLES),
            'content' => $paragraphs,
        ];
    }
}

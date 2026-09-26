<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * @var array<int, string>
     */
    private const TITLES = [
        'Laravel入門ハンズオン',
        'Docker勉強会',
        'Webエンジニアのためのキャリア相談会',
        'もくもく会(オンライン)',
        'Git・GitHub基礎講座',
        'ポートフォリオ発表会',
        'PHPカンファレンス報告会',
        'データベース設計ワークショップ',
    ];

    /**
     * @var array<int, string>
     */
    private const LOCATIONS = [
        '東京 渋谷コワーキングスペース',
        '大阪 梅田会議室',
        '名古屋 栄イベントホール',
        '福岡 天神カンファレンスルーム',
        'オンライン(Zoom)',
    ];

    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(self::TITLES),
            'description' => "初心者の方も歓迎です。実際に手を動かしながら学びます。\n\n"
                . "当日は筆記用具とノートパソコンをご持参ください。\n"
                . '質疑応答の時間も設けていますので、お気軽にご参加ください。',
            'location' => fake()->randomElement(self::LOCATIONS),
            'starts_at' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'capacity' => fake()->numberBetween(10, 50),
        ];
    }
}

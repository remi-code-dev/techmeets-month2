<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => '限定Tシャツ・トートバッグ 先行販売会',
                'description' => "公式オンラインストア発売に先駆けて、限定デザインのTシャツとトートバッグを先行販売します。\n\n"
                    . "・会場限定カラーあり\n・お一人様各3点まで\n・先行販売購入特典:ステッカー付き",
                'location' => '東京 渋谷ポップアップストア',
                'starts_at' => now()->addWeeks(2)->setTime(11, 0),
                'capacity' => 50,
            ],
            [
                'title' => '周年記念アクリルスタンド 先行予約会',
                'description' => "周年記念の描き下ろしアクリルスタンドを、一般発売の前にご予約いただけます。\n\n"
                    . "・全5種、ランダムではなく選んで購入OK\n・ご予約の方には限定ポストカードをプレゼント",
                'location' => '大阪 梅田イベントスペース',
                'starts_at' => now()->addWeeks(4)->setTime(13, 0),
                'capacity' => 30,
            ],
            [
                'title' => '冬の新作グッズ 先行販売(オンライン抽選)',
                'description' => "冬の新作パーカー・マフラー・マグカップを、オンラインで先行販売します。\n\n"
                    . "・参加者のみ先行購入リンクをご案内\n・数量限定のため、お早めにご予約ください",
                'location' => 'オンライン(Zoom)',
                'starts_at' => now()->addWeeks(6)->setTime(19, 0),
                'capacity' => 100,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}

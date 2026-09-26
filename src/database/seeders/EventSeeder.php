<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::factory(8)->create()->each(function (Event $event) {
            Reservation::factory(2)->create(['event_id' => $event->id]);
        });
    }
}

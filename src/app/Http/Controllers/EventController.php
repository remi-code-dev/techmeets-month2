<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * イベント一覧
     */
    public function index(): View
    {
        $events = Event::orderBy('starts_at')->paginate(10);

        return view('events.index', compact('events'));
    }

    /**
     * イベント詳細
     */
    public function show(Event $event): View
    {
        return view('events.show', compact('event'));
    }
}

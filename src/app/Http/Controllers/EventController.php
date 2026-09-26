<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
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
     * イベント作成フォームの表示
     */
    public function create(): View
    {
        return view('events.create');
    }

    /**
     * イベントの保存
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $event = Event::create($request->validated());

        return redirect()
            ->route('events.show', $event)
            ->with('status', 'イベントを作成しました。');
    }

    /**
     * イベント詳細
     */
    public function show(Event $event): View
    {
        return view('events.show', compact('event'));
    }
}

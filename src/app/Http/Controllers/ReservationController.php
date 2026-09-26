<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * 予約一覧
     */
    public function index(): View
    {
        $reservations = Reservation::with('event')->latest()->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    /**
     * 予約フォームの表示
     */
    public function create(Event $event): View
    {
        return view('reservations.create', compact('event'));
    }

    /**
     * 予約の保存
     */
    public function store(StoreReservationRequest $request, Event $event): RedirectResponse
    {
        $event->reservations()->create($request->validated());

        return redirect()
            ->route('reservations.index')
            ->with('status', '予約を受け付けました。');
    }

    /**
     * 予約のキャンセル
     */
    public function cancel(Reservation $reservation): RedirectResponse
    {
        if (! $reservation->isCancelled()) {
            $reservation->cancelled_at = now();
            $reservation->save();
        }

        return redirect()
            ->route('reservations.index')
            ->with('status', '予約をキャンセルしました。');
    }
}

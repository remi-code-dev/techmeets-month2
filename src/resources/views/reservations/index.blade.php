@extends('layouts.app')

@section('title', '予約一覧')

@section('content')
    <h1 class="mb-4">予約一覧</h1>

    @if ($reservations->isEmpty())
        <p class="text-muted">予約はありません。</p>
    @else
        <div class="table-responsive mb-4">
            <table class="table table-striped align-middle bg-white">
                <thead>
                    <tr>
                        <th>イベント</th>
                        <th>名前</th>
                        <th>メール</th>
                        <th>人数</th>
                        <th>予約日時</th>
                        <th>状態</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td><a href="{{ route('events.show', $reservation->event) }}">{{ $reservation->event->title }}</a></td>
                            <td>{{ $reservation->name }}</td>
                            <td>{{ $reservation->email }}</td>
                            <td>{{ $reservation->guests }}</td>
                            <td>{{ $reservation->reserved_at->format('Y/m/d H:i') }}</td>
                            <td>
                                @if ($reservation->isCancelled())
                                    <span class="badge bg-secondary">キャンセル済み</span>
                                @else
                                    <span class="badge bg-success">予約中</span>
                                @endif
                            </td>
                            <td>
                                @unless ($reservation->isCancelled())
                                    <form action="{{ route('reservations.cancel', $reservation) }}" method="POST"
                                          onsubmit="return confirm('この予約をキャンセルしますか？');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">キャンセル</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $reservations->links() }}
    @endif
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">掲示板</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="p-4 rounded-md bg-green-50 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="p-6 bg-white shadow sm:rounded-lg">
                @auth
                    <form action="{{ route('comments.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="body" value="投稿内容" />
                            <textarea
                                name="body"
                                id="body"
                                rows="4"
                                maxlength="1000"
                                required
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >{{ old('body') }}</textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <x-primary-button>投稿する</x-primary-button>
                    </form>
                @else
                    <p class="text-sm text-gray-600">
                        投稿するには
                        <a href="{{ route('login') }}" class="underline text-indigo-600">ログイン</a>
                        または
                        <a href="{{ route('register') }}" class="underline text-indigo-600">ユーザー登録</a>
                        してください。
                    </p>
                @endauth
            </div>

            @forelse ($comments as $comment)
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <p class="text-gray-900 whitespace-pre-wrap break-words">{{ $comment->body }}</p>

                    <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                        <span>
                            {{ $comment->user->name }}
                            /
                            {{ $comment->created_at->format('Y/m/d H:i') }}
                        </span>

                        @can('delete', $comment)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                  onsubmit="return confirm('この投稿を削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">削除</button>
                            </form>
                        @endcan
                    </div>
                </div>
            @empty
                <p class="text-gray-500">まだ投稿がありません。</p>
            @endforelse

            {{ $comments->links() }}
        </div>
    </div>
</x-app-layout>

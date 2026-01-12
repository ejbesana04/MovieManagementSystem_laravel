<x-layouts.app :title="__('Movie Trash')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">Movie Trash</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Manage deleted movies — restore them to the library or delete them permanently.
                </p>
            </div>
            <a href="{{ route('dashboard') }}"
               class="rounded-lg bg-neutral-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-neutral-700">
                Back to Dashboard
            </a>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex items-center gap-4">
                <div class="rounded-full bg-red-100 p-3 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Movies in Trash</p>
                    <h3 class="mt-1 text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ $movies->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex h-full flex-col p-6">
                <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Recently Deleted</h2>

                @if($movies->isEmpty())
                    <div class="flex flex-1 items-center justify-center rounded-lg border-2 border-dashed border-neutral-300 p-12 dark:border-neutral-600">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-neutral-100">Trash is empty</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">No deleted movies found.</p>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b border-neutral-200 dark:border-neutral-700">
                                <tr>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Poster</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Title</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Genre</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Deleted At</th>
                                    <th class="pb-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach($movies as $movie)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                        <td class="py-4">
                                            @if($movie->photo)
                                                <img src="{{ asset('storage/' . $movie->photo) }}"
                                                     alt="{{ $movie->title }}"
                                                     class="h-12 w-9 rounded object-cover shadow-sm">
                                            @else
                                                <div class="flex h-12 w-9 items-center justify-center rounded bg-neutral-200 dark:bg-neutral-700">
                                                    <span class="text-xs font-medium text-neutral-600 dark:text-neutral-300">N/A</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                                {{ $movie->title }}
                                            </div>
                                            <div class="text-xs text-neutral-500">
                                                {{ $movie->release_year }}
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            @if($movie->genre)
                                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                    {{ $movie->genre->name }}
                                                </span>
                                            @else
                                                <span class="text-xs text-neutral-500 italic">Uncategorized</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ $movie->deleted_at->format('M d, Y') }}
                                            <div class="text-xs text-neutral-500">
                                                {{ $movie->deleted_at->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <div class="flex justify-end gap-2">
                                                <form method="POST" action="{{ route('movies.restore', $movie->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="rounded-lg bg-green-600 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-green-700"
                                                            onclick="return confirm('Restore this movie?')">
                                                        Restore
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('movies.force-delete', $movie->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-red-700"
                                                            onclick="return confirm('PERMANENTLY delete this movie? This action is final.')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
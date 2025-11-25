<x-layouts.app :title="__('Genres')">

    {{-- Main Container --}}
    <div class="flex h-full w-full flex-1 flex-col gap-8 p-6 lg:p-10 bg-gray-50 text-gray-800">

        {{-- Flash Message --}}
        @if(session('success'))
            <div id="flash-message"
                class="rounded-xl bg-green-100 p-4 text-green-700 shadow-md border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- PHP Styles --}}
        @php
            $cardClass = "rounded-2xl bg-white p-6 shadow-xl border border-gray-200";
            $labelClass = "mb-2 block text-sm font-medium text-gray-700";
            $inputClass = "w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-base 
                            focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 
                            text-gray-800 placeholder-gray-400";
        @endphp

        {{-- Add Genre Form --}}
        <div class="{{ $cardClass }}">
            <h2 class="text-2xl font-bold text-gray-900 border-b pb-3 mb-6 border-gray-200">
                🎬 Add New Genre
            </h2>

            <form action="{{ route('genres.store') }}" method="POST" class="grid gap-6 md:grid-cols-2">
                @csrf

                {{-- Name --}}
                <div>
                    <label class="{{ $labelClass }}">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Enter genre name" class="{{ $inputClass }}">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="{{ $labelClass }}">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                        placeholder="Enter description (optional)" class="{{ $inputClass }}">
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Button --}}
                <div class="md:col-span-2 flex justify-start pt-3">
                    <button type="submit"
                        class="rounded-xl bg-blue-600 px-6 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-500 shadow-md">
                        Add Genre
                    </button>
                </div>
            </form>
        </div>

        {{-- Genre List Table --}}
        <div class="{{ $cardClass }}">
            <div class="flex items-center justify-between mb-6 border-b pb-3 border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">📋 Genre List</h2>
            </div>

            {{-- Table with gradient header --}}
            <div class="overflow-x-hidden rounded-xl border border-gray-200 shadow-sm">
                <table class="w-full min-w-full table-auto">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50 sticky top-0">
                            <th class="px-4 py-3 text-left text-sm font-bold rounded-tl-xl">#</th>
                            <th class="px-4 py-3 text-left text-sm font-bold">Name</th>
                            <th class="px-4 py-3 text-left text-sm font-bold">Description</th>
                            <th class="px-4 py-3 text-center text-sm font-bold">Movies</th>
                            <th class="px-4 py-3 text-left text-sm font-bold rounded-tr-xl">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($genres as $genre)
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-normal">
                                    {{ $genre->name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 whitespace-normal">
                                    {{ $genre->description ?: '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-600">
                                    {{ $genre->movies_count }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center space-x-3">
                                        {{-- Edit --}}
                                        <button onclick="editGenre({{ $genre->id }}, '{{ addslashes($genre->name) }}', '{{ addslashes($genre->description) }}')"
                                            class="flex items-center gap-1 text-blue-600 hover:text-blue-800 p-1 font-medium">
                                            <i data-lucide="square-pen" class="h-4 w-4"></i> Edit
                                        </button>
                                        <span class="text-gray-300">|</span>
                                        {{-- Delete --}}
                                        <form action="{{ route('genres.destroy', $genre) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Delete genre {{ $genre->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center gap-1 text-red-600 hover:text-red-800 p-1 font-medium">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                    No genres found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div id="editGenreModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
            <div class="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Edit Genre</h2>
                <form id="editGenreForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4">
                        <div>
                            <label class="{{ $labelClass }}">Name</label>
                            <input type="text" id="edit_name" name="name" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Description</label>
                            <input type="text" id="edit_description" name="description" class="{{ $inputClass }}">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeGenreModal()"
                            class="rounded-xl border border-gray-300 bg-gray-100 px-5 py-2 text-base font-medium text-gray-700">
                            Cancel
                        </button>
                        <button type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-2 text-base font-medium text-white hover:bg-blue-500">
                            Update Genre
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- JS --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        function editGenre(id, name, description) {
            document.getElementById('editGenreForm').action = `/genres/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            openGenreModal();
        }

        function openGenreModal() {
            document.getElementById('editGenreModal').classList.remove('hidden');
            document.getElementById('editGenreModal').classList.add('flex');
        }

        function closeGenreModal() {
            document.getElementById('editGenreModal').classList.add('hidden');
        }

        // Auto fade-out flash message
        const flash = document.getElementById('flash-message');
        if (flash) {
            setTimeout(() => {
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }, 3000);
        }
    </script>

</x-layouts.app>

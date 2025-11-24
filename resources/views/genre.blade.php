<x-layouts.app :title="__('Genres')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4">

        {{-- Flash Message --}}
        @if(session('success'))
            <div id="flash-message" class="rounded-xl bg-green-50 p-4 text-green-700 shadow-md dark:bg-green-900/30 dark:text-green-300">
                
                {{ session('success') }}
            </div>
        @endif

        {{-- Main Content Wrapper --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-2xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex h-full flex-col p-8"> 

                {{-- Add Genre Form --}}
                <div class="mb-8 rounded-xl bg-neutral-50 p-6 dark:bg-neutral-900/50">
                    
                    <h2 class="mb-6 text-xl font-bold text-neutral-900 dark:text-neutral-100">🎬 Add New Genre</h2>

                    <form action="{{ route('genres.store') }}" method="POST" class="grid gap-6 md:grid-cols-2">
                        @csrf
                        {{-- Name --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter genre name" required
                                    class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                                    focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                                    dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                            
                            @error('name')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Description</label>
                            <input type="text" name="description" value="{{ old('description') }}" placeholder="Enter description (optional)"
                                    class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                                    focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                                    dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                            
                            @error('description')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Submit Button --}}
                        <div class="md:col-span-2 flex justify-start pt-2">
                            <button type="submit" class="rounded-xl bg-blue-600 px-8 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/40">
                                
                                Add Genre
                            </button>
                        </div>
                    </form>
                </div>
                
                {{-- Genre List Table --}}
                <div class="flex-1 overflow-auto">
                    <h2 class="mb-4 text-xl font-bold text-neutral-900 dark:text-neutral-100">📋 Genre List</h2>
                    <div class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-900">
                                    <th class="px-4 py-3 text-center text-sm font-bold text-neutral-700 dark:text-neutral-300">#</th> 
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Name</th> 
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Description</th> 
                                    <th class="px-4 py-3 text-center text-sm font-bold text-neutral-700 dark:text-neutral-300">Movies Count</th> 
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Actions</th> 
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @forelse($genres as $genre)
                                    <tr class="transition-colors hover:bg-blue-50/50 dark:hover:bg-neutral-700/50"> 
                                        <td class="px-4 py-3 text-center text-sm text-neutral-600 dark:text-neutral-400">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-left text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ $genre->name }}</td>
                                        <td class="px-4 py-3 text-left text-sm text-neutral-600 dark:text-neutral-400">{{ $genre->description ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center text-sm text-neutral-600 dark:text-neutral-400">{{ $genre->movies_count }}</td>
                                        
                                        {{-- Actions Cell --}}
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center space-x-3">
                                                {{-- EDIT BUTTON --}}
                                                <button onclick="editGenre({{ $genre->id }}, '{{ addslashes($genre->name) }}', '{{ addslashes($genre->description) }}')"
                                                        class="group flex items-center gap-1 text-blue-600 font-medium transition-colors hover:text-blue-700 
                                                                focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-md p-1 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <i data-lucide="square-pen" class="h-4 w-4"></i>
                                                    Edit
                                                </button>

                                                {{-- Separator --}}
                                                <span class="text-neutral-300 dark:text-neutral-600">|</span>

                                                {{-- DELETE BUTTON --}}
                                                <form action="{{ route('genres.destroy', $genre) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete the genre: {{ addslashes($genre->name) }}? This will affect {{ $genre->movies_count }} movies.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="group flex items-center gap-1 text-red-600 font-medium transition-colors hover:text-red-700 
                                                                    focus:outline-none focus:ring-2 focus:ring-red-500/20 rounded-md p-1 dark:text-red-400 dark:hover:text-red-300">
                                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        {{-- End Actions Cell --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            No genres found. Add your first genre above!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Edit Genre Modal --}}
    <div id="editGenreModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-xl rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="mb-4 text-xl font-bold text-neutral-900 dark:text-neutral-100">Edit Genre</h2>

            <form id="editGenreForm" method="POST">
                @csrf
                @method('PUT')

                <div class="grid gap-4">
                    {{-- Name --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Name</label>
                        <input type="text" id="edit_name" name="name" required 
                            class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                            focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Description</label>
                        <input type="text" id="edit_description" name="description" 
                            class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                            focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                    </div>
                </div>

                {{-- Modal Actions --}}
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditGenreModal()"
                            class="rounded-xl border border-neutral-300 bg-white px-5 py-2 text-base font-medium text-neutral-700 transition-colors hover:bg-neutral-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-2 text-base font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/40">
                        Update Genre
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Lucide Icons Script and JavaScript Logic --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        // Flash Message Dismissal Logic (Copied from Movie Dashboard)
        document.addEventListener('DOMContentLoaded', () => {
            const flashMessage = document.getElementById('flash-message');
            
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.transition = 'opacity 0.5s ease-out';
                    flashMessage.style.opacity = '0';
                    
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500); 
                }, 3000); 
            }
        });
        
        // Modal functions for Genre (Updated to match Movie Dashboard logic)
        function editGenre(id, name, description) {
            document.getElementById('editGenreModal').classList.remove('hidden');
            document.getElementById('editGenreModal').classList.add('flex');
            document.getElementById('editGenreForm').action = `/genres/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
        }

        function closeEditGenreModal() {
            document.getElementById('editGenreModal').classList.add('hidden');
            document.getElementById('editGenreModal').classList.remove('flex');
        }
    </script>
</x-layouts.app>
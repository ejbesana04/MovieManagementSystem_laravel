<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4"> {{-- Increased gap from 4 to 6 for better spacing --}}

        {{-- Flash Message (Kept original structure for functionality) --}}
        @if(session('success'))
            <div id="flash-message" class="rounded-xl bg-green-50 p-4 text-green-700 shadow-md dark:bg-green-900/30 dark:text-green-300">
                {{-- Updated rounded-lg to rounded-xl and added shadow for a modern feel --}}
                {{ session('success') }}
            </div>
        @endif

        {{-- Dashboard Cards --}}
        <div class="grid auto-rows-min gap-6 md:grid-cols-3"> {{-- Increased gap to 6 --}}

            {{-- Total Movies --}}
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg transition-transform duration-200 hover:scale-[1.01] dark:bg-neutral-800">
                {{-- Increased rounded-xl to rounded-2xl. Removed border-neutral-200 and added shadow-lg for lift. --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-medium text-neutral-500 dark:text-neutral-400">Total Movies</p>
                        {{-- Increased text-sm to text-base --}}
                        <h3 class="mt-2 text-4xl font-extrabold text-neutral-900 dark:text-neutral-100">{{ $movies->count() }}</h3>
                        {{-- Increased text-3xl to text-4xl and added font-extrabold --}}
                    </div>

                    {{-- Updated Icon Styling: Use a subtle accent ring/background --}}
                    <div class="rounded-2xl border-4 border-blue-500/10 bg-blue-50 p-3 dark:bg-blue-900/10">
                        <i data-lucide="film" class="h-7 w-7 text-blue-600 dark:text-blue-400"></i>
                        {{-- Increased size to h-7 w-7 --}}
                    </div>
                </div>
            </div>

            {{-- Total Genres --}}
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg transition-transform duration-200 hover:scale-[1.01] dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-medium text-neutral-500 dark:text-neutral-400">Total Genres</p>
                        <h3 class="mt-2 text-4xl font-extrabold text-neutral-900 dark:text-neutral-100">{{ $genres->count() }}</h3>
                    </div>

                    {{-- Updated Icon Styling --}}
                    <div class="rounded-2xl border-4 border-green-500/10 bg-green-50 p-3 dark:bg-green-900/10">
                        <i data-lucide="layers" class="h-7 w-7 text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            {{-- Average Rating --}}
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg transition-transform duration-200 hover:scale-[1.01] dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-medium text-neutral-500 dark:text-neutral-400">Average Rating</p>
                        <h3 class="mt-2 text-4xl font-extrabold text-neutral-900 dark:text-neutral-100">
                            {{ number_format($movies->avg('rating') ?: 0, 1) }}
                        </h3>
                    </div>

                    {{-- Updated Icon Styling --}}
                    <div class="rounded-2xl border-4 border-purple-500/10 bg-purple-50 p-3 dark:bg-purple-900/10">
                        <i data-lucide="star" class="h-7 w-7 text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Content Wrapper --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-2xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            {{-- Increased rounded-xl to rounded-2xl --}}
            <div class="flex h-full flex-col p-8"> {{-- Increased padding from p-6 to p-8 --}}
                
                {{-- Add New Movie Form Container --}}
                <div class="mb-8 rounded-xl bg-neutral-50 p-6 dark:bg-neutral-900/50">
                    {{-- Increased bottom margin to mb-8, adjusted border-neutral-200 to be on the outer wrapper --}}
                    <h2 class="mb-6 text-xl font-bold text-neutral-900 dark:text-neutral-100">Add New Movie</h2>
                    {{-- Increased text-lg to text-xl and mb-4 to mb-6 --}}

                    <form action="{{ route('movies.store') }}" method="POST" class="grid gap-6 md:grid-cols-2">
                        {{-- Increased form grid gap to 6 --}}
                        @csrf

                        {{-- Title --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter movie title" required 
                                class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                                dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                            {{-- MODIFICATION: rounded-lg to rounded-xl, py-2 to py-3, text-sm to text-base, improved focus ring --}}
                            @error('title')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Director --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Director</label>
                            <input type="text" name="director" value="{{ old('director') }}" placeholder="Enter director" 
                                class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                                dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                            @error('director')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Release Year --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Release Year</label>
                            <input type="number" name="release_year" value="{{ old('release_year') }}" placeholder="e.g., 2023" 
                                class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                                dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                            @error('release_year')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Rating --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Rating</label>
                            <input type="number" step="0.1" max="10" min="0" name="rating" value="{{ old('rating') }}" placeholder="e.g., 8.5" 
                                class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                                dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                            @error('rating')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Genre --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Genre</label>
                            <select name="genre_id" required 
                                class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                                dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                                {{-- MODIFICATION: rounded-lg to rounded-xl, py-2 to py-3, text-sm to text-base, improved focus ring --}}
                                <option value="">Select a genre</option>
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                        {{ $genre->name }} ({{ $genre->description ?? 'No description' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('genre_id')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Synopsis --}}
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Synopsis</label>
                            <textarea name="synopsis" placeholder="Enter synopsis" 
                                class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base h-24
                                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                                dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">{{ old('synopsis') }}</textarea>
                            {{-- MODIFICATION: Added h-24 for defined height, rounded-lg to rounded-xl, py-2 to py-3, text-sm to text-base, improved focus ring --}}
                            @error('synopsis')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="md:col-span-2 flex justify-start pt-2">
                            <button type="submit" class="rounded-xl bg-blue-600 px-8 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/40">
                                {{-- MODIFICATION: rounded-lg to rounded-xl, px-6 to px-8, py-2 to py-3, text-sm to text-base, added font-semibold, improved focus ring --}}
                                Add Movie
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Movie List Table --}}
                <div class="flex-1 overflow-auto">
                    <h2 class="mb-4 text-xl font-bold text-neutral-900 dark:text-neutral-100">Movie List</h2>
                    {{-- Increased text-lg to text-xl --}}
                    <div class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
                        {{-- Added rounded-xl and border for better containment --}}
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-900">
                                    {{-- MODIFICATION: bg-neutral-50 to bg-neutral-100 for a slightly lighter header --}}
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">#</th>
                                    {{-- font-semibold to font-bold --}}
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Title</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Genre</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Year</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Rating</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Director</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Synopsis</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @forelse($movies as $movie)
                                    <tr class="transition-colors hover:bg-blue-50/50 dark:hover:bg-neutral-700/50">
                                        {{-- MODIFICATION: hover:bg-neutral-50 to a subtle blue/neutral hover color for visual interest --}}
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ $movie->title }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $movie->genre?->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $movie->release_year ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $movie->rating ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $movie->director ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400 max-w-md">{{ Str::limit($movie->synopsis, 80) ?? '-' }}</td>
                                        {{-- Used Str::limit to prevent huge columns --}}
                                        
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center space-x-3">
                                                {{-- 1. MODERNIZED EDIT BUTTON (Text with subtle icon appearance) --}}
                                                <button onclick="editMovie({{ $movie->id }}, '{{ $movie->title }}', {{ $movie->genre_id ?? 'null' }}, '{{ $movie->release_year }}', '{{ $movie->rating }}', '{{ $movie->director }}', '{{ addslashes($movie->synopsis) }}')"
                                                        class="group flex items-center gap-1 text-blue-600 font-medium transition-colors hover:text-blue-700 
                                                               focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-md p-1 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <i data-lucide="square-pen" class="h-4 w-4"></i> {{-- Added Lucide edit icon --}}
                                                    Edit
                                                </button>

                                                {{-- Separator --}}
                                                <span class="text-neutral-300 dark:text-neutral-600">|</span>

                                                {{-- 2. MODERNIZED DELETE BUTTON (Subtle warning treatment) --}}
                                                <form action="{{ route('movies.destroy', $movie) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this movie?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="group flex items-center gap-1 text-red-600 font-medium transition-colors hover:text-red-700 
                                                                   focus:outline-none focus:ring-2 focus:ring-red-500/20 rounded-md p-1 dark:text-red-400 dark:hover:text-red-300">
                                                        <i data-lucide="trash-2" class="h-4 w-4"></i> {{-- Added Lucide trash icon --}}
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            No movies found. Add your first movie above!
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
    
    {{-- Edit Modal (Updated with rounded-xl, py-3 inputs, and improved button styling) --}}
    <div id="editMovieModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-2xl rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">
            {{-- MODIFICATION: rounded-xl to rounded-2xl, added shadow-2xl --}}
            <h2 class="mb-4 text-xl font-bold text-neutral-900 dark:text-neutral-100">Edit Movie</h2>

            <form id="editMovieForm" method="POST">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    {{-- Title --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Title</label>
                        <input type="text" id="edit_title" name="title" required 
                            class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                            focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                    </div>

                    {{-- Director --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Director</label>
                        <input type="text" id="edit_director" name="director" 
                            class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                            focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                    </div>

                    {{-- Release Year --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Release Year</label>
                        <input type="number" id="edit_release_year" name="release_year" 
                            class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                            focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                    </div>

                    {{-- Rating --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Rating</label>
                        <input type="number" step="0.1" max="10" min="0" id="edit_rating" name="rating" 
                            class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                            focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                    </div>

                    {{-- Genre --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Genre</label>
                        <select id="edit_genre_id" name="genre_id" 
                            class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                            focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                            <option value="">Select a genre</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Synopsis --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Synopsis</label>
                        <textarea id="edit_synopsis" name="synopsis" 
                            class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base h-24
                            focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                            dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500"></textarea>
                    </div>
                </div>

                {{-- Modal Actions --}}
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditMovieModal()"
                            class="rounded-xl border border-neutral-300 bg-white px-5 py-2 text-base font-medium text-neutral-700 transition-colors hover:bg-neutral-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-2 text-base font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/40">
                        Update Movie
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Lucide Icons Script and Flash Message Logic --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        // Existing modal functions (kept the same for functionality)
        function editMovie(id, title, genreId, release_year, rating, director, synopsis) {
            document.getElementById('editMovieModal').classList.remove('hidden');
            document.getElementById('editMovieModal').classList.add('flex');
            document.getElementById('editMovieForm').action = `/movies/${id}`;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_director').value = director;
            document.getElementById('edit_release_year').value = release_year;
            document.getElementById('edit_rating').value = rating;
            document.getElementById('edit_genre_id').value = genreId || '';
            document.getElementById('edit_synopsis').value = synopsis;
        }

        function closeEditMovieModal() {
            document.getElementById('editMovieModal').classList.add('hidden');
            document.getElementById('editMovieModal').classList.remove('flex');
        }

        // Flash Message Dismissal Logic (Kept the same for functionality)
        document.addEventListener('DOMContentLoaded', () => {
            const flashMessage = document.getElementById('flash-message');
            
            if (flashMessage) {
                // Set a timer to remove the element after 3000 milliseconds (3 seconds)
                setTimeout(() => {
                    flashMessage.style.transition = 'opacity 0.5s ease-out';
                    flashMessage.style.opacity = '0';
                    
                    // After the fade-out, actually remove the element from the DOM
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500); // 500ms should match the transition time
                }, 3000); // Wait 3 seconds before starting the fade
            }
        });
    </script>
</x-layouts.app>
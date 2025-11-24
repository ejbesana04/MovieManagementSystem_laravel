<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4">

        {{-- Flash Message --}}
        @if(session('success'))
            <div id="flash-message" class="rounded-xl bg-green-50 p-4 text-green-700 shadow-md dark:bg-green-900/30 dark:text-green-300">
                
                {{ session('success') }}
            </div>
        @endif

        {{-- Dashboard Cards --}}
        <div class="grid auto-rows-min gap-6 md:grid-cols-3"> 

            {{-- Total Movies --}}
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg transition-transform duration-200 hover:scale-[1.01] dark:bg-neutral-800">
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-medium text-neutral-500 dark:text-neutral-400">Total Movies</p>
                        
                        <h3 class="mt-2 text-4xl font-extrabold text-neutral-900 dark:text-neutral-100">{{ $movies->count() }}</h3>
                        
                    </div>

                    {{-- Updated Icon Styling --}}
                    <div class="rounded-2xl border-4 border-blue-500/10 bg-blue-50 p-3 dark:bg-blue-900/10">
                        <i data-lucide="film" class="h-7 w-7 text-blue-600 dark:text-blue-400"></i>
                        
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
            
            <div class="flex h-full flex-col p-8"> 
                
                {{-- Add New Movie Form Container --}}
                <div class="mb-8 rounded-xl bg-neutral-50 p-6 dark:bg-neutral-900/50">
                    
                    <h2 class="mb-6 text-xl font-bold text-neutral-900 dark:text-neutral-100">Add New Movie</h2>

                    <form action="{{ route('movies.store') }}" method="POST" class="grid gap-6 md:grid-cols-2">
                        
                        @csrf

                        {{-- Title --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter movie title" required 
                                class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-base 
                                focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 
                                dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:focus:border-blue-500">
                            
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
                            
                            @error('synopsis')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="md:col-span-2 flex justify-start pt-2">
                            <button type="submit" class="rounded-xl bg-blue-600 px-8 py-3 text-base font-semibold text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/40">
                                
                                Add Movie
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Movie List Table --}}
                <div class="flex-1 overflow-auto">
                    <h2 class="mb-4 text-xl font-bold text-neutral-900 dark:text-neutral-100">Movie List</h2>
                    
                    <div class="overflow-x-auto rounded-xl border border-neutral-200 dark:border-neutral-700">
                        
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-900">
                                    
                                    <th class="px-4 py-3 text-left text-sm font-bold text-neutral-700 dark:text-neutral-300">#</th>
                                    
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
                                        
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ $movie->title }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $movie->genre?->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $movie->release_year ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $movie->rating ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $movie->director ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400 max-w-md">{{ Str::limit($movie->synopsis, 80) ?? '-' }}</td>
                                        
                                        
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center space-x-3">
                                                
                                                <button onclick="editMovie({{ $movie->id }}, '{{ $movie->title }}', {{ $movie->genre_id ?? 'null' }}, '{{ $movie->release_year }}', '{{ $movie->rating }}', '{{ $movie->director }}', '{{ addslashes($movie->synopsis) }}')"
                                                        class="group flex items-center gap-1 text-blue-600 font-medium transition-colors hover:text-blue-700 
                                                               focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-md p-1 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <i data-lucide="square-pen" class="h-4 w-4"></i> {{-- Added Lucide edit icon --}}
                                                    Edit
                                                </button>

                                                {{-- Separator --}}
                                                <span class="text-neutral-300 dark:text-neutral-600">|</span>

                                                
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
    
    {{-- Edit Modal --}}
    <div id="editMovieModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-2xl rounded-2xl border border-neutral-200 bg-white p-6 shadow-2xl dark:border-neutral-700 dark:bg-neutral-800">
            
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
                    
                    
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500); 
                }, 3000); 
            }
        });
    </script>
</x-layouts.app>
<x-layouts.app :title="__('Dashboard')">
    
    <div class="flex h-full w-full flex-1 flex-col gap-8 p-6 lg:p-10 bg-gray-50 text-gray-800">

        {{-- Flash Message --}}
        @if(session('success'))
            <div id="flash-message" class="rounded-xl bg-green-100 p-4 text-green-700 shadow-md border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- PHP Variable Definitions --}}
        @php
            // Light Theme Styles
            $cardClass = "relative overflow-hidden rounded-2xl bg-white p-6 shadow-xl border border-gray-200 transition-transform duration-200 hover:scale-[1.01] hover:shadow-blue-100";
            $iconBaseClass = "rounded-2xl border-4 p-3";
            $textMuted = "text-base font-medium text-gray-500";
            $textBold = "mt-2 text-4xl font-extrabold text-gray-900";
            $inputClass = "w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-base " . 
                            "focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 " .
                            "text-gray-800 placeholder-gray-400 disabled:bg-gray-100 disabled:text-gray-500"; 
            $labelClass = "mb-2 block text-sm font-medium text-gray-700";
            $errorClass = "mt-1 text-xs text-red-500";
            $headerClass = "mb-6 text-2xl font-bold text-gray-900 border-b pb-3 border-gray-200";
        @endphp

        {{-- 1. Full-Width Stats Bar (3 Cards) --}}
        <div class="grid auto-rows-min gap-6 md:grid-cols-3"> 
            {{-- Total Movies --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-center justify-between">
                    <div><p class="{{ $textMuted }}">Total Movies</p><h3 class="{{ $textBold }}">{{ $movies->count() }}</h3></div>
                    <div class="{{ $iconBaseClass }} border-blue-100 bg-blue-50"><i data-lucide="film" class="h-8 w-8 text-blue-600"></i></div>
                </div>
            </div>

            {{-- Total Genres --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-center justify-between">
                    <div><p class="{{ $textMuted }}">Total Genres</p><h3 class="{{ $textBold }}">{{ $genres->count() }}</h3></div>
                    <div class="{{ $iconBaseClass }} border-green-100 bg-green-50"><i data-lucide="layers" class="h-8 w-8 text-green-600"></i></div>
                </div>
            </div>

            {{-- Average Rating --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-center justify-between">
                    <div><p class="{{ $textMuted }}">Average Rating</p><h3 class="{{ $textBold }}">{{ number_format($movies->avg('rating') ?: 0, 1) }}</h3></div>
                    <div class="{{ $iconBaseClass }} border-purple-100 bg-purple-50"><i data-lucide="star" class="h-8 w-8 text-purple-600"></i></div>
                </div>
            </div>
        </div>

        

        {{-- 2. Visual Content Grid (Movie Cards, Charts, and Activity) --}}
        <div class="grid gap-8 lg:grid-cols-3 flex-1 items-stretch">
            
            {{-- LEFT SECTION: Movie Posters/Cards (2/3 width) --}}
            <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-xl border border-gray-200 min-h-[400px]">
                
                <div class="flex items-center justify-between mb-6 border-b pb-3 border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">⭐ Top Rated Movies</h2>
                    
                    {{-- Button to Trigger Add Movie Modal --}}
                    <button type="button" 
                            onclick="resetModalForAdd()"
                            class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-500 shadow-md flex items-center gap-1 shrink-0">
                        <i data-lucide="plus-circle" class="h-4 w-4"></i> Add Movie
                    </button>
                </div>
                
                {{-- Movie Card Grid (Showing up to 4 featured movies) --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @forelse($movies->sortByDesc('rating')->take(4) as $movie)
        <div class="group relative rounded-lg overflow-hidden cursor-pointer hover:shadow-blue-200 transition-shadow duration-300 aspect-[2/3] bg-gray-100">
            
            {{-- START: LOCAL STATIC POSTER IMAGE ASSIGNED IN ORDER --}}
            @php
                // Assigns the poster number based on the loop index (0, 1, 2, 3) + 1
                // Box 1 gets poster-1.jpg
                // Box 2 gets poster-2.jpg
                // Box 3 gets poster-3.jpg
                // Box 4 gets poster-4.jpg
                $imageNumber = $loop->index + 1; 
                $imagePath = 'images/poster-' . $imageNumber . '.jpg';
            @endphp
            
            <img src="{{ asset($imagePath) }}" 
                 alt="{{ $movie->title }} Poster Placeholder" 
                 class="w-full h-full object-cover transition-transform duration-300 ease-out transform group-hover:scale-105 group-hover:translate-y-[-3px]">
            {{-- END: LOCAL STATIC POSTER IMAGE ASSIGNED IN ORDER --}}

            {{-- Hover Overlay --}}
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-3">
                <p class="text-sm font-bold text-white">{{ $movie->title }} ({{ $movie->release_year }})</p>
                <p class="text-xs text-yellow-300 flex items-center gap-1">
                    <i data-lucide="star" class="h-3 w-3 fill-yellow-300"></i> {{ $movie->rating }}
                </p>
                {{-- Call the new dedicated viewMovieDetails function --}}
                <button onclick="viewMovieDetails('{{ addslashes($movie->title) }}', '{{ $movie->genre?->name ?? 'N/A' }}', '{{ $movie->release_year }}', '{{ $movie->rating }}', '{{ addslashes($movie->director) }}', '{{ addslashes($movie->synopsis) }}')"
                        class="mt-2 text-xs text-blue-300 hover:text-blue-100 self-start">
                    View Details
                </button>
            </div>
        </div>
    @empty
        <p class="text-gray-500 col-span-4">No movies to display. Use the button above to add one!</p>
    @endforelse
</div>
            </div>

            {{-- RIGHT SECTION: Activity Only (1/3 width) --}}
            <div class="lg:col-span-1 space-y-8">
                
                {{-- Recent Activity --}}
                <div class="rounded-2xl bg-white p-6 shadow-xl border border-gray-200 h-full">
                    <h3 class="text-xl font-bold text-gray-900 border-b pb-3 border-gray-200">🔍 Recent Activity</h3>
                    <ul class="mt-4 space-y-3">
                        <li class="flex items-start gap-2 text-sm text-gray-700"><i data-lucide="check-circle" class="h-4 w-4 text-green-600 mt-1 shrink-0"></i> Movie **'Dune'** updated (1 min ago)</li>
                        <li class="flex items-start gap-2 text-sm text-gray-700"><i data-lucide="plus" class="h-4 w-4 text-blue-600 mt-1 shrink-0"></i> Movie **'Inception'** added (3 hrs ago)</li>
                        <li class="flex items-start gap-2 text-sm text-gray-700"><i data-lucide="alert-triangle" class="h-4 w-4 text-orange-600 mt-1 shrink-0"></i> **DB backup** overdue (8 hrs ago)</li>
                        <li class="flex items-start gap-2 text-sm text-gray-700"><i data-lucide="check-circle" class="h-4 w-4 text-green-600 mt-1 shrink-0"></i> Movie **'Pulp Fiction'** edited (Yesterday)</li>
                        <li class="flex items-start gap-2 text-sm text-gray-700"><i data-lucide="trash-2" class="h-4 w-4 text-red-600 mt-1 shrink-0"></i>Movie <strong>'Avatar'</strong> removed (2 days ago)</li>
                        <li class="flex items-start gap-2 text-sm text-gray-700"><i data-lucide="edit-3" class="h-4 w-4 text-purple-600 mt-1 shrink-0"></i>Category <strong>'Sci-Fi'</strong> updated (3 days ago)</li>
                        <li class="flex items-start gap-2 text-sm text-gray-700"><i data-lucide="upload" class="h-4 w-4 text-purple-600 mt-1 shrink-0"></i>New poster for <strong>'The Matrix'</strong> (5 days ago)</li>
                        <li class="flex items-start gap-2 text-sm text-gray-700"><i data-lucide="database" class="h-4 w-4 text-green-600 mt-1 shrink-0"></i>Database optimized (6 days ago)</li>
                        <li class="flex items-start gap-2 text-sm text-gray-700"><i data-lucide="key" class="h-4 w-4 text-orange-600 mt-1 shrink-0"></i>Admin password updated (1 week ago)</li>

                    </ul>
                </div>

            </div>
        </div>

        

        {{-- 3. Detailed Movie List Table Section --}}
<div class="flex-1 rounded-2xl bg-white p-8 shadow-xl border border-gray-200 h-fit">

    {{-- Table Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b pb-3 border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900">📊 Movie List</h2>
    </div>

    {{-- Table Wrapper --}}
    <div class="w-full overflow-x-hidden rounded-xl border border-gray-200 shadow-sm">
        <table class="w-full table-auto">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50 sticky top-0">
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">#</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Title</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Genre</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Year</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Rating</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Director</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Synopsis</th>
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($movies as $movie)
                    <tr class="transition-colors hover:bg-blue-50">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $movie->title }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $movie->genre?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $movie->release_year ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-yellow-600">{{ $movie->rating ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $movie->director ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ Str::limit($movie->synopsis, 80) ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm min-w-[120px]">
                            <div class="flex items-center space-x-3">
                                {{-- Edit --}}
                                <button onclick="editMovie({{ $movie->id }}, '{{ addslashes($movie->title) }}', {{ $movie->genre_id ?? 'null' }}, '{{ $movie->release_year }}', '{{ $movie->rating }}', '{{ addslashes($movie->director) }}', '{{ addslashes($movie->synopsis) }}')"
                                    class="group flex items-center gap-1 text-blue-600 font-medium transition-colors hover:text-blue-800 rounded-md p-1">
                                    <i data-lucide="square-pen" class="h-4 w-4"></i> Edit
                                </button>

                                <span class="text-gray-300">|</span>

                                {{-- Delete --}}
                                <form action="{{ route('movies.destroy', $movie) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this movie?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                             class="group flex items-center gap-1 text-red-600 font-medium transition-colors hover:text-red-800 rounded-md p-1">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">
                            No movies found. Add a new movie to populate your vault.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>




    </div>
    
    {{-- Original Edit/Add Modal --}}
    <div id="editMovieModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-2xl rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl">
            
            <h2 id="modalTitle" class="mb-4 text-xl font-bold text-gray-900">Add New Movie</h2>

            <form id="editMovieForm" method="POST">
                @csrf
                @method('PUT') 

                <div class="grid gap-4 md:grid-cols-2">
                    {{-- Title --}}
                    <div><label class="{{ $labelClass }}">Title</label><input type="text" id="edit_title" name="title" required class="{{ $inputClass }}"></div>
                    {{-- Director --}}
                    <div><label class="{{ $labelClass }}">Director</label><input type="text" id="edit_director" name="director" class="{{ $inputClass }}"></div>
                    {{-- Release Year --}}
                    <div><label class="{{ $labelClass }}">Release Year</label><input type="number" id="edit_release_year" name="release_year" class="{{ $inputClass }}"></div>
                    {{-- Rating --}}
                    <div><label class="{{ $labelClass }}">Rating (0-10)</label><input type="number" step="0.1" max="10" min="0" id="edit_rating" name="rating" class="{{ $inputClass }}"></div>
                    {{-- Genre --}}
                    <div>
                        <label class="{{ $labelClass }}">Genre</label>
                        <select id="edit_genre_id" name="genre_id" class="{{ $inputClass }}">
                            <option value="" class="text-gray-400">Select a genre</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" class="text-gray-800">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Synopsis --}}
                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Synopsis</label>
                        <textarea id="edit_synopsis" name="synopsis" class="h-24 {{ $inputClass }}"></textarea>
                    </div>
                </div>

                {{-- Modal Actions --}}
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditMovieModal()"
                            class="rounded-xl border border-gray-300 bg-gray-100 px-5 py-2 text-base font-medium text-gray-700 transition-colors hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" id="modalSubmitButton"
                            class="rounded-xl bg-blue-600 px-5 py-2 text-base font-medium text-white transition-colors hover:bg-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-200">
                        Add Movie
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- View Details Modal --}}
    <div id="viewDetailsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-2xl rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl">
            <h2 id="viewModalTitle" class="mb-6 text-2xl font-bold text-gray-900 border-b pb-3">Movie Details</h2>

            <div class="grid gap-4 md:grid-cols-2">
                
                {{-- Title --}}
                <div>
                    <p class="{{ $labelClass }} text-sm font-semibold">Title</p>
                    <p id="view_title" class="text-lg font-bold text-gray-800"></p>
                </div>
                
                {{-- Director --}}
                <div>
                    <p class="{{ $labelClass }} text-sm font-semibold">Director</p>
                    <p id="view_director" class="text-lg text-gray-800"></p>
                </div>

                {{-- Release Year --}}
                <div>
                    <p class="{{ $labelClass }} text-sm font-semibold">Release Year</p>
                    <p id="view_release_year" class="text-lg text-gray-800"></p>
                </div>

                {{-- Rating --}}
                <div>
                    <p class="{{ $labelClass }} text-sm font-semibold">Rating</p>
                    <p class="text-lg text-yellow-600 flex items-center gap-1">
                        <i data-lucide="star" class="h-5 w-5 fill-yellow-600"></i>
                        <span id="view_rating"></span>
                    </p>
                </div>

                {{-- Genre --}}
                <div class="md:col-span-2">
                    <p class="{{ $labelClass }} text-sm font-semibold">Genre</p>
                    <p id="view_genre" class="text-lg text-gray-800"></p>
                </div>

                {{-- Synopsis --}}
                <div class="md:col-span-2">
                    <p class="{{ $labelClass }} text-sm font-semibold">Synopsis</p>
                    <p id="view_synopsis" class="text-base text-gray-700 leading-relaxed max-h-40 overflow-y-auto"></p>
                </div>
            </div>

            {{-- Modal Actions --}}
            <div class="mt-6 flex justify-end">
                <button type="button" onclick="closeViewDetailsModal()"
                        class="rounded-xl border border-gray-300 bg-blue-600 px-5 py-2 text-base font-medium text-white transition-colors hover:bg-blue-700">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- Scripting --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // --- Shared Functions ---

        function getDomElements() {
            const form = document.getElementById('editMovieForm');
            return {
                // Edit Modal Elements
                editModal: document.getElementById('editMovieModal'),
                form: form,
                modalTitle: document.getElementById('modalTitle'),
                submitButton: document.getElementById('modalSubmitButton'),
                methodField: form ? form.querySelector('input[name="_method"]') : null,
                editTitle: document.getElementById('edit_title'),
                editDirector: document.getElementById('edit_director'),
                editReleaseYear: document.getElementById('edit_release_year'),
                editRating: document.getElementById('edit_rating'),
                editGenreId: document.getElementById('edit_genre_id'),
                editSynopsis: document.getElementById('edit_synopsis'),

                // View Modal Elements 
                viewModal: document.getElementById('viewDetailsModal'),
                viewModalTitle: document.getElementById('viewModalTitle'),
                viewTitle: document.getElementById('view_title'),
                viewDirector: document.getElementById('view_director'),
                viewReleaseYear: document.getElementById('view_release_year'),
                viewRating: document.getElementById('view_rating'),
                viewGenre: document.getElementById('view_genre'),
                viewSynopsis: document.getElementById('view_synopsis'),
            };
        }

        // --- Edit/Add Modal Handlers ---

        function openEditModal() {
            const { editModal } = getDomElements();
            if (editModal) {
                editModal.classList.remove('hidden');
                editModal.classList.add('flex');
            }
        }

        function closeEditMovieModal() {
            const { editModal } = getDomElements();
            if (editModal) {
                editModal.classList.add('hidden');
                editModal.classList.remove('flex');
            }
        }

        function resetModalForAdd() {
            const { form, modalTitle, submitButton, methodField, editTitle, editDirector, editReleaseYear, editRating, editGenreId, editSynopsis } = getDomElements(); 
            
            if (!form) return; 
            
            modalTitle.textContent = 'Add New Movie';
            form.action = "{{ route('movies.store') }}"; 
            submitButton.textContent = 'Add Movie';
            
            // Set method to POST for the store route
            if (methodField) {
                 methodField.disabled = true;
                 methodField.value = 'POST';
            }
            
            form.reset(); 
            // Re-enable all fields for adding
            [editTitle, editDirector, editReleaseYear, editRating, editGenreId, editSynopsis].forEach(el => {
                if (el) el.disabled = false;
            });
            
            openEditModal();
        }

        function editMovie(id, title, genreId, release_year, rating, director, synopsis) {
            const { form, modalTitle, submitButton, methodField, editTitle, editDirector, editReleaseYear, editRating, editGenreId, editSynopsis } = getDomElements(); 

            if (!form) return; 

            modalTitle.textContent = 'Edit Movie: ' + title;
            form.action = `/movies/${id}`; 
            submitButton.textContent = 'Update Movie';
            
            // Set method to PUT for the update route
            if (methodField) {
                 methodField.disabled = false;
                 methodField.value = 'PUT';
            }
            
            // Populate fields
            editTitle.value = title;
            editDirector.value = director;
            editReleaseYear.value = release_year;
            editRating.value = rating;
            editGenreId.value = genreId || '';
            editSynopsis.value = synopsis;

            // Enable fields for editing
            [editTitle, editDirector, editReleaseYear, editRating, editGenreId, editSynopsis].forEach(el => {
                if (el) el.disabled = false;
            });

            openEditModal();
        }

        // --- View Details Modal Handlers ---

        function openViewModal() {
            const { viewModal } = getDomElements();
            if (viewModal) {
                viewModal.classList.remove('hidden');
                viewModal.classList.add('flex');
                setTimeout(() => lucide.createIcons(), 100); 
            }
        }

        function closeViewDetailsModal() {
            const { viewModal } = getDomElements();
            if (viewModal) {
                viewModal.classList.add('hidden');
                viewModal.classList.remove('flex');
            }
        }

        function viewMovieDetails(title, genreName, release_year, rating, director, synopsis) {
            const { viewModalTitle, viewTitle, viewDirector, viewReleaseYear, viewRating, viewGenre, viewSynopsis } = getDomElements();

            // Populate the view modal fields
            viewModalTitle.textContent = 'Movie Details: ' + title;
            viewTitle.textContent = title;
            viewDirector.textContent = director || 'N/A';
            viewReleaseYear.textContent = release_year || 'N/A';
            viewRating.textContent = rating || 'N/A';
            viewGenre.textContent = genreName || 'N/A';
            viewSynopsis.textContent = synopsis || 'No synopsis provided.';
            
            openViewModal();
        }

        // --- Initialization ---

        function initializeDashboardScripts() {
            lucide.createIcons();

            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.transition = 'opacity 0.5s ease-out';
                    flashMessage.style.opacity = '0';
                    setTimeout(() => flashMessage.remove(), 500);
                }, 3000);
            }
        }

        document.addEventListener('DOMContentLoaded', initializeDashboardScripts);
        document.addEventListener('livewire:navigated', initializeDashboardScripts);
        document.addEventListener('turbo:load', initializeDashboardScripts);
        document.addEventListener('turbolinks:load', initializeDashboardScripts);

    </script>
</x-layouts.app>
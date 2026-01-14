<x-layouts.app :title="__('Dashboard')">
    
    <div class="flex h-full w-full flex-1 flex-col gap-8 p-6 lg:p-10 bg-slate-50/50 text-slate-800 font-sans relative">

        {{-- Flash Message (Floating Toast) --}}
        @if(session('success'))
            <div id="flash-message" class="fixed top-6 right-6 z-[100] rounded-xl bg-emerald-500 text-white px-4 py-3 shadow-xl flex items-center gap-2 animate-in slide-in-from-right-5 transition-all duration-500">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        {{-- PHP Definitions --}}
        @php
            // Updated Card Style to match Genre/Trash aesthetic
            $cardClass = "group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-slate-200";
            
            // Icon Backgrounds
            $iconBaseClass = "rounded-xl p-3 flex items-center justify-center transition-colors";
            
            // Text Styles
            $textMuted = "text-xs font-bold text-slate-400 uppercase tracking-wider";
            $textBold = "mt-2 text-3xl font-black text-slate-800";
            
            // Standard Inputs
            $inputClass = "w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold shadow-sm transition-all focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10 text-slate-800 placeholder-slate-400"; 
            
            $headerClass = "flex items-center justify-between mb-6 border-b border-slate-100 pb-4";
            $labelClass = "block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2";
        @endphp

        {{-- 1. Full-Width Stats Bar --}}
        <div class="grid auto-rows-min gap-6 md:grid-cols-3"> 
            {{-- Total Movies --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="{{ $textMuted }}">Library Size</p>
                        <h3 class="{{ $textBold }}">{{ $movies->count() }} <span class="text-lg font-medium text-slate-400">Films</span></h3>
                    </div>
                    <div class="{{ $iconBaseClass }} bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white">
                        <i data-lucide="film" class="h-6 w-6"></i>
                    </div>
                </div>
                {{-- Decorative background icon --}}
                <i data-lucide="film" class="absolute -bottom-4 -right-4 h-32 w-32 text-slate-50 opacity-50 group-hover:opacity-100 transition-opacity rotate-12"></i>
            </div>

            {{-- Total Genres --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="{{ $textMuted }}">Categories</p>
                        <h3 class="{{ $textBold }}">{{ $genres->count() }} <span class="text-lg font-medium text-slate-400">Genres</span></h3>
                    </div>
                    <div class="{{ $iconBaseClass }} bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white">
                        <i data-lucide="layers" class="h-6 w-6"></i>
                    </div>
                </div>
                 <i data-lucide="layers" class="absolute -bottom-4 -right-4 h-32 w-32 text-slate-50 opacity-50 group-hover:opacity-100 transition-opacity rotate-12"></i>
            </div>

            {{-- Average Rating --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="{{ $textMuted }}">Avg Quality</p>
                        <h3 class="{{ $textBold }}">{{ number_format($movies->avg('rating') ?: 0, 1) }} <span class="text-lg font-medium text-slate-400">/10</span></h3>
                    </div>
                    <div class="{{ $iconBaseClass }} bg-amber-50 text-amber-500 group-hover:bg-amber-500 group-hover:text-white">
                        <i data-lucide="star" class="h-6 w-6 fill-current"></i>
                    </div>
                </div>
                <i data-lucide="star" class="absolute -bottom-4 -right-4 h-32 w-32 text-slate-50 opacity-50 group-hover:opacity-100 transition-opacity rotate-12"></i>
            </div>
        </div>

        {{-- 2. Visual Content Grid --}}
        <div class="grid gap-8 lg:grid-cols-3 flex-1 items-stretch">
            
            {{-- LEFT: Featured Movies --}}
            <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <div class="{{ $headerClass }}">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <span class="bg-amber-100 p-1.5 rounded-lg"><i data-lucide="award" class="h-5 w-5 text-amber-600"></i></span>
                        Top Rated Films
                    </h2>
                    <button type="button" onclick="resetModalForAdd()" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-slate-200 hover:bg-blue-600 hover:shadow-blue-200 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <i data-lucide="plus" class="h-4 w-4"></i> Add Film
                    </button>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse($featuredMovies as $movie)
                        <div class="group relative rounded-xl overflow-hidden cursor-pointer shadow-sm border border-slate-100 bg-slate-50 hover:shadow-xl hover:shadow-blue-900/10 transition-all duration-300 aspect-[2/3]">
                             {{-- Dynamic Poster Logic --}}
                             @if($movie->photo)
                                <img src="{{ Storage::url($movie->photo) }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                             @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-300">
                                    <i data-lucide="image" class="h-10 w-10 mb-2"></i>
                                    <span class="text-xs font-bold uppercase">No Poster</span>
                                </div>
                             @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 p-4 flex flex-col justify-end">
                                <h4 class="text-white font-bold text-sm leading-tight">{{ $movie->title }}</h4>
                                <div class="flex items-center gap-2 mt-2 mb-3">
                                    <span class="text-xs font-bold text-slate-300">{{ $movie->release_year }}</span>
                                    <span class="text-xs font-bold text-amber-400 flex items-center gap-1 bg-white/10 px-1.5 py-0.5 rounded backdrop-blur-md">
                                        <i data-lucide="star" class="h-3 w-3 fill-amber-400"></i> {{ $movie->rating }}
                                    </span>
                                </div>
                                <button onclick="viewMovieDetails(@js($movie))"
        class="w-full rounded-lg bg-white py-2 text-xs font-bold text-slate-900 hover:bg-blue-50 hover:text-blue-600 transition-colors">
    View Details
</button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 py-12 flex flex-col items-center justify-center text-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <i data-lucide="film" class="h-8 w-8 text-slate-300 mb-2"></i>
                            <p class="text-slate-500 font-bold text-sm">No top rated movies yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- RIGHT: Activity --}}
            <div class="lg:col-span-1 rounded-2xl bg-white p-6 shadow-sm border border-slate-100 h-full">
                <div class="{{ $headerClass }}">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <span class="bg-blue-100 p-1.5 rounded-lg"><i data-lucide="activity" class="h-5 w-5 text-blue-600"></i></span>
                        Activity
                    </h3>
                </div>
                
                <div class="relative pl-4 space-y-6 before:absolute before:left-[23px] before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-100">
                    @php
                        $activities = [
                            ['icon' => 'check-circle-2', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-100', 'text' => "Movie <strong>'Dune'</strong> updated", 'time' => '1 min ago'],
                            ['icon' => 'plus', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100', 'text' => "Movie <strong>'Inception'</strong> added", 'time' => '3 hrs ago'],
                            ['icon' => 'alert-triangle', 'color' => 'text-amber-600', 'bg' => 'bg-amber-100', 'text' => "<strong>DB backup</strong> overdue", 'time' => '8 hrs ago'],
                            ['icon' => 'trash-2', 'color' => 'text-red-600', 'bg' => 'bg-red-100', 'text' => "Movie <strong>'Avatar'</strong> deleted", 'time' => '2 days ago'],
                        ];
                    @endphp
                    @foreach($activities as $act)
                        <div class="relative flex items-start gap-4">
                            <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border-2 border-white {{ $act['bg'] }} shadow-sm">
                                <i data-lucide="{{ $act['icon'] }}" class="h-5 w-5 {{ $act['color'] }}"></i>
                            </div>
                            <div class="pt-1">
                                <p class="text-sm text-slate-600 leading-snug">{!! $act['text'] !!}</p>
                                <p class="text-xs font-bold text-slate-400 mt-1">{{ $act['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 3. Search Bar --}}
        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
            <form action="{{ route('movies.index') }}" method="GET" class="grid gap-4 md:grid-cols-12 items-end">
                <div class="md:col-span-5 relative">
                    <label class="{{ $labelClass }}">Search Vault</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-4 top-3.5 h-5 w-5 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Find title, director..." class="{{ $inputClass }} pl-11">
                    </div>
                </div>
                <div class="md:col-span-4">
                    <label class="{{ $labelClass }}">Filter Genre</label>
                    <div class="relative">
                        <i data-lucide="filter" class="absolute left-4 top-3.5 h-5 w-5 text-slate-400"></i>
                        <select name="genre_filter" class="{{ $inputClass }} pl-11 appearance-none cursor-pointer">
                            <option value="">All Categories</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" {{ request('genre_filter') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="md:col-span-3 flex gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-slate-900 px-4 py-3 text-sm font-bold text-white shadow hover:bg-slate-800 transition-colors">Apply</button>
                    <a href="{{ route('movies.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-colors">Clear</a>
                </div>
            </form>
        </div>

        {{-- 4. IMPROVED FILM LIST --}}
        <div class="rounded-2xl bg-white shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="list-music" class="h-5 w-5 text-slate-400"></i> Film List
                </h2>
                <form method="GET" action="{{ route('movies.export') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="genre_filter" value="{{ request('genre_filter') }}">
                    <button type="submit" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">
                        <i data-lucide="file-down" class="h-4 w-4"></i> Export PDF
                    </button>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider w-16">#</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider w-32">Poster</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Movie Details</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Genre</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($movies as $movie)
                            <tr class="group hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-400">{{ $loop->iteration }}</td>
                                
                                {{-- VISIBLE POSTER COLUMN --}}
                                <td class="px-6 py-4">
                                    <div class="h-24 w-16 rounded-lg bg-slate-100 shadow-sm border border-slate-200 overflow-hidden relative">
                                        @if($movie->photo)
                                            <img src="{{ Storage::url($movie->photo) }}" alt="{{ $movie->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-slate-50">
                                                <i data-lucide="image" class="h-6 w-6 text-slate-300"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <p class="font-bold text-slate-800 text-lg group-hover:text-blue-700 transition-colors">{{ $movie->title }}</p>
                                    <div class="flex items-center gap-3 mt-1 text-sm text-slate-500">
                                        <span class="flex items-center gap-1"><i data-lucide="calendar" class="h-3.5 w-3.5"></i> {{ $movie->release_year }}</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="flex items-center gap-1"><i data-lucide="user" class="h-3.5 w-3.5"></i> {{ $movie->director }}</span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 align-middle">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-xs font-bold text-slate-600">
                                        {{ $movie->genre?->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex items-center gap-1.5 font-bold {{ $movie->rating >= 8 ? 'text-emerald-600' : ($movie->rating >= 5 ? 'text-amber-600' : 'text-slate-400') }}">
                                        <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                                        {{ $movie->rating }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 align-middle text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <button
    class="p-2 rounded-lg text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors edit-btn"
    data-movie='@json($movie)'>
    <i data-lucide="pencil" class="h-4 w-4"></i>
</button>

                                        
                                        <form action="{{ route('movies.destroy', $movie) }}" method="POST" class="inline" onsubmit="return confirm('Move to trash?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-12 text-center text-slate-400 font-bold">No movies found in your vault.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($movies, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $movies->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODALS SECTION (Kept exactly as functional requirements dictate) --}}
    
    {{-- Add Movie Modal --}}
    <div id="addMovieModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl p-8 animate-in zoom-in-95">
            <div class="flex justify-between items-center mb-6">
                <h2 id="addModalTitle" class="text-2xl font-black text-slate-800">New Entry</h2>
                <button onclick="closeAddMovieModal()" class="p-2 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600"><i data-lucide="x" class="h-6 w-6"></i></button>
            </div>
            <form id="addMovieForm" method="POST" action="{{ route('movies.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid md:grid-cols-2 gap-6">
                    <div><label class="{{ $labelClass }}">Title</label><input type="text" id="add_title" name="title" required class="{{ $inputClass }}" placeholder="Movie Title"></div>
                    <div><label class="{{ $labelClass }}">Director</label><input type="text" id="add_director" name="director" class="{{ $inputClass }}" placeholder="Director Name"></div>
                    <div><label class="{{ $labelClass }}">Year</label><input type="number" id="add_release_year" name="release_year" class="{{ $inputClass }}" placeholder="2024"></div>
                    <div><label class="{{ $labelClass }}">Rating (0-10)</label><input type="number" step="0.1" id="add_rating" name="rating" class="{{ $inputClass }}" placeholder="8.5"></div>
                    <div>
                        <label class="{{ $labelClass }}">Genre</label>
                        <select id="add_genre_id" name="genre_id" class="{{ $inputClass }}">
                            <option value="">Select Genre...</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Synopsis</label>
                        <textarea id="add_synopsis" name="synopsis" class="{{ $inputClass }} h-24"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Poster Image</label>
                        <input type="file" name="photo" id="add_photo" class="{{ $inputClass }} !py-2 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold file:text-slate-700 hover:file:bg-slate-200">
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeAddMovieModal()" class="px-6 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50">Cancel</button>
                    <button type="submit" id="addModalSubmitButton" class="px-6 py-2.5 rounded-xl bg-slate-900 text-white font-bold hover:bg-blue-600 shadow-lg">Save Film</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Movie Modal --}}
    <div id="editMovieModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity">
        <div class="w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-black text-slate-800">Edit Details</h2>
                <button onclick="closeEditMovieModal()" class="p-2 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600"><i data-lucide="x" class="h-6 w-6"></i></button>
            </div>
            <form id="editMovieForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="grid md:grid-cols-2 gap-6">
                    <div><label class="{{ $labelClass }}">Title</label><input type="text" id="edit_title" name="title" required class="{{ $inputClass }}"></div>
                    <div><label class="{{ $labelClass }}">Director</label><input type="text" id="edit_director" name="director" class="{{ $inputClass }}"></div>
                    <div><label class="{{ $labelClass }}">Year</label><input type="number" id="edit_release_year" name="release_year" class="{{ $inputClass }}"></div>
                    <div><label class="{{ $labelClass }}">Rating</label><input type="number" step="0.1" id="edit_rating" name="rating" class="{{ $inputClass }}"></div>
                    <div>
                        <label class="{{ $labelClass }}">Genre</label>
                        <select id="edit_genre_id" name="genre_id" class="{{ $inputClass }}">
                            <option value="">Select Genre</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Synopsis</label>
                        <textarea id="edit_synopsis" name="synopsis" class="{{ $inputClass }} h-24"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Update Poster</label>
                        <div id="currentPhotoPreview" class="mb-3"></div>
                        <input type="file" name="photo" id="edit_photo" class="{{ $inputClass }} !py-2 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-bold file:text-slate-700 hover:file:bg-slate-200">
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" onclick="closeEditMovieModal()" class="px-6 py-2.5 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50">Cancel</button>
                    <button type="submit" id="modalSubmitButton" class="px-6 py-2.5 rounded-xl bg-slate-900 text-white font-bold hover:bg-blue-600 shadow-lg">Update Film</button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Modal (Same Logic) --}}
    <div id="viewDetailsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden relative">
            <div class="h-32 bg-slate-900 w-full relative overflow-hidden">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cube-coat.png')]"></div>
                <button onclick="closeViewDetailsModal()" class="absolute top-4 right-4 bg-white/10 p-2 rounded-full text-white hover:bg-white/20"><i data-lucide="x" class="h-5 w-5"></i></button>
            </div>
            <div class="px-8 pb-8">
                <div class="flex justify-between items-end -mt-12 mb-6 relative z-10">
                    <div>
                        <div class="bg-white p-1 rounded-xl shadow-lg inline-block mb-4">
                            <div class="h-24 w-16 bg-slate-200 rounded-lg flex items-center justify-center overflow-hidden">
                                <i data-lucide="film" class="h-8 w-8 text-slate-400"></i>
                            </div>
                        </div>
                        <h2 id="view_title" class="text-3xl font-black text-slate-800 leading-none"></h2>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-1 text-amber-500 font-black text-2xl justify-end">
                            <span id="view_rating"></span> <i data-lucide="star" class="h-5 w-5 fill-current"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rating</span>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Director</p>
                        <p id="view_director" class="font-bold text-slate-800"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Year</p>
                        <p id="view_release_year" class="font-bold text-slate-800"></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Genre</p>
                        <p id="view_genre" class="font-bold text-slate-800"></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Synopsis</p>
                        <p id="view_synopsis" class="text-slate-600 leading-relaxed text-sm font-medium"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
/**
 * Helper to fetch all UI elements to keep code clean
 */
function getDomElements() {
    return {
        // Modals
        editModal: document.getElementById('editMovieModal'),
        addModal: document.getElementById('addMovieModal'),
        viewModal: document.getElementById('viewDetailsModal'),
        
        // Forms
        editForm: document.getElementById('editMovieForm'),
        addForm: document.getElementById('addMovieForm'),
        
        // Form Parts
        modalTitle: document.getElementById('modalTitle'),
        addModalTitle: document.getElementById('addModalTitle'),
        modalSubmitButton: document.getElementById('modalSubmitButton'),
        addModalSubmitButton: document.getElementById('addModalSubmitButton'),
        currentPhotoPreview: document.getElementById('currentPhotoPreview'),
        
        // Inputs (Edit)
        editTitle: document.getElementById('edit_title'),
        editDirector: document.getElementById('edit_director'),
        editReleaseYear: document.getElementById('edit_release_year'),
        editRating: document.getElementById('edit_rating'),
        editGenreId: document.getElementById('edit_genre_id'),
        editSynopsis: document.getElementById('edit_synopsis'),
        
        // View Details Fields
        viewTitle: document.getElementById('view_title'),
        viewDirector: document.getElementById('view_director'),
        viewReleaseYear: document.getElementById('view_release_year'),
        viewRating: document.getElementById('view_rating'),
        viewGenre: document.getElementById('view_genre'),
        viewSynopsis: document.getElementById('view_synopsis'),
    };
}

/* ===========================
   EDIT & VIEW HANDLERS
=========================== */

// Delegation for Edit Buttons
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.edit-btn');
    if (!btn) return;

    try {
        const movie = JSON.parse(btn.dataset.movie);
        // Use the route attribute from the button for better URL handling
        const actionUrl = btn.dataset.route || `/movies/${movie.id}`;
        openEditFromData(movie, actionUrl);
    } catch (err) {
        console.error("Error parsing movie JSON:", err);
    }
});

function openEditFromData(movie, actionUrl) {
    const els = getDomElements();
    if (!els.editForm) return;

    els.modalTitle.textContent = 'Edit Details';
    els.editForm.action = actionUrl;
    els.modalSubmitButton.textContent = 'Update Film';

    // Populate Fields
    els.editTitle.value = movie.title ?? '';
    els.editDirector.value = movie.director ?? '';
    els.editReleaseYear.value = movie.release_year ?? '';
    els.editRating.value = movie.rating ?? '';
    els.editGenreId.value = movie.genre_id ?? '';
    els.editSynopsis.value = movie.synopsis ?? '';

    // Handle Image Preview
    if (els.currentPhotoPreview) {
        els.currentPhotoPreview.innerHTML = movie.photo 
            ? `<div class="flex items-center gap-4 rounded-xl border border-slate-200 p-3 bg-white">
                <img src="/storage/${movie.photo}" class="h-16 w-16 rounded-lg object-cover">
                <p class="text-xs font-bold text-slate-500">Current Poster</p>
               </div>`
            : `<div class="p-3 text-xs text-slate-400 border border-dashed rounded-xl text-center">No poster set</div>`;
    }

    openEditModal();
}

function viewMovieDetails(movie) {
    const els = getDomElements();
    els.viewTitle.textContent = movie.title;
    els.viewDirector.textContent = movie.director || 'N/A';
    els.viewReleaseYear.textContent = movie.release_year || 'N/A';
    els.viewRating.textContent = movie.rating || '0';
    els.viewGenre.textContent = (movie.genre ? movie.genre.name : null) || 'N/A';
    els.viewSynopsis.textContent = movie.synopsis || 'No synopsis available.';
    openViewModal();
}

/* ===========================
   ADD MOVIE LOGIC
=========================== */

function resetModalForAdd() {
    const els = getDomElements();
    if (!els.addForm) return;

    els.addModalTitle.textContent = 'New Entry';
    els.addForm.action = "{{ route('movies.store') }}";
    els.addModalSubmitButton.textContent = 'Save Film';

    els.addForm.reset();
    
    // Safety: Clear photo preview if it exists in the add form context
    if (els.currentPhotoPreview) els.currentPhotoPreview.innerHTML = '';
    
    openAddMovieModal();
}

/* ===========================
   MODAL TOGGLES
=========================== */

function openEditModal() {
    const { editModal } = getDomElements();
    editModal.classList.replace('hidden', 'flex');
    setTimeout(() => lucide.createIcons(), 50);
}

function closeEditMovieModal() {
    getDomElements().editModal.classList.replace('flex', 'hidden');
}

function openAddMovieModal() {
    const { addModal } = getDomElements();
    if (addModal) {
        addModal.classList.replace('hidden', 'flex');
        setTimeout(() => lucide.createIcons(), 50);
    }
}

function closeAddMovieModal() {
    const { addModal } = getDomElements();
    if (addModal) addModal.classList.replace('flex', 'hidden');
}

function openViewModal() {
    const { viewModal } = getDomElements();
    viewModal.classList.replace('hidden', 'flex');
    setTimeout(() => lucide.createIcons(), 50);
}

function closeViewDetailsModal() {
    getDomElements().viewModal.classList.replace('flex', 'hidden');
}

/* ===========================
   INITIALIZATION
=========================== */

document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    // Auto-hide Flash Toast
    const flash = document.getElementById('flash-message');
    if (flash) {
        setTimeout(() => {
            flash.style.opacity = '0';
            flash.style.transform = 'translateX(20px)';
            setTimeout(() => flash.remove(), 500);
        }, 4000);
    }
});
</script>

</x-layouts.app>
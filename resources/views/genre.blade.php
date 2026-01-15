<x-layouts.app :title="__('Genres')">

    {{-- Add Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="flex h-full w-full flex-1 flex-col gap-8 p-6 lg:p-10 font-sans bg-slate-50/50">

        {{-- Flash Message --}}
        @if(session('success'))
            <div id="flash-message" class="fixed top-6 right-6 z-50 rounded-xl bg-emerald-500 text-white px-4 py-3 shadow-xl flex items-center gap-2 animate-in slide-in-from-right-5">
                <i class="fas fa-check-circle text-lg"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Add Genre "Ticket" Card --}}
        <div class="relative overflow-hidden rounded-2xl bg-white shadow-xl shadow-slate-200/50 ring-1 ring-black/5">
            {{-- Decorative Header --}}
            <div class="bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 px-8 py-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 flex items-center justify-center"><i class="fas fa-tags text-8xl text-white transform rotate-12"></i></div>
                <div class="relative z-10">
                    <h2 class="text-2xl font-black text-white flex items-center gap-3">
                        <span class="p-2 bg-white/20 rounded-lg backdrop-blur-sm flex items-center justify-center"><i class="fas fa-plus text-white text-xl"></i></span>
                        New Category
                    </h2>
                    <p class="text-violet-100 text-sm mt-2 font-medium max-w-lg">Create a new genre to organize your film library. Genres help filter and analyze your collection effectively.</p>
                </div>
            </div>

            <div class="p-8">
                <form action="{{ route('genres.store') }}" method="POST" class="flex flex-col md:flex-row gap-6 items-end">
                    @csrf
                    
                    <div class="flex-1 w-full">
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Genre Name</label>
                        <div class="relative">
                            <i class="fas fa-font absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Science Fiction" 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm font-semibold focus:border-violet-500 focus:bg-white focus:ring-4 focus:ring-violet-500/10 transition-all">
                        </div>
                        
                    </div>

                    <div class="flex-[2] w-full">
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Description <span class="text-slate-300 font-normal">(Optional)</span></label>
                        <div class="relative">
                            <i class="fas fa-file-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="description" value="{{ old('description') }}" placeholder="Brief description of this category..." 
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 pr-4 py-3 text-sm font-semibold focus:border-violet-500 focus:bg-white focus:ring-4 focus:ring-violet-500/10 transition-all">
                        </div>
                    </div>

                    <div class="w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto rounded-xl bg-slate-900 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-slate-300 hover:bg-violet-600 hover:shadow-violet-200 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                            Create Genre
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Divider --}}
        <div class="flex items-center gap-4 py-2">
            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-book text-slate-400"></i> Catalog
            </h3>
            <div class="h-px bg-slate-200 flex-1"></div>
            <span class="text-xs font-bold text-slate-400 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">{{ $genres->count() }} Categories</span>
        </div>

        {{-- Genre Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($genres as $genre)
                <div class="group relative bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                    {{-- Color strip on top --}}
                    <div class="absolute top-0 left-6 right-6 h-1 bg-gradient-to-r from-violet-500 to-fuchsia-500 rounded-b-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-lg font-black text-slate-700 group-hover:bg-violet-50 group-hover:text-violet-600 group-hover:border-violet-100 transition-colors">
                            {{ substr($genre->name, 0, 1) }}
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <button onclick="editGenre(@js($genre), '{{ route('genres.update', $genre->id) }}')" 
                            class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 hover:text-blue-600 transition-colors"
                            ><i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('genres.destroy', $genre) }}" method="POST" onsubmit="return confirm('Delete {{ $genre->name }}?')">
                                @csrf @method('DELETE')
                                <button class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <h4 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-violet-700 transition-colors">{{ $genre->name }}</h4>
                    <p class="text-sm text-slate-500 leading-relaxed line-clamp-2 min-h-[2.5rem]">{{ $genre->description ?: 'No description provided.' }}</p>
                    
                    <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Movies in Genre</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                            <i class="fas fa-film text-xs"></i> {{ $genre->movies_count }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50/50">
                    <div class="bg-white p-4 rounded-full shadow-sm mb-4 flex items-center justify-center"><i class="fas fa-layer-group text-slate-300 text-3xl"></i></div>
                    <p class="text-slate-500 font-bold">No genres found.</p>
                    <p class="text-sm text-slate-400 mt-1">Create your first category above.</p>
                </div>
            @endforelse
        </div>

        {{-- Edit Modal --}}
<div id="editGenreModal" 
     class="fixed inset-0 z-50 {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity">
    
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl p-8 animate-in zoom-in-95">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800">Edit Genre</h2>
            <button onclick="closeGenreModal()" class="text-slate-400 hover:text-slate-600 flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- If there's an error, we need to ensure the form action is still set --}}
        <form id="editGenreForm" method="POST" action="{{ session('edit_url') ?? '' }}">
            @csrf @method('PUT')
            
            <div class="space-y-5">
                <div>
                    <label class="text-xs font-bold uppercase text-slate-500 block mb-2">Name</label>
                    <input type="text" id="edit_name" name="name" 
                        value="{{ old('name') }}"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm font-semibold focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all @error('name') border-red-500 ring-red-500/10 @enderror">
                    
                    {{-- THIS IS THE ERROR MESSAGE --}}
                    @error('name')
                        <p class="mt-1 text-xs font-bold text-red-500 animate-pulse">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-bold uppercase text-slate-500 block mb-2">Description</label>
                    <input type="text" id="edit_description" name="description" 
                        value="{{ old('description') }}"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm font-semibold focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all">
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-6">
                <button type="button" onclick="closeGenreModal()" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl bg-violet-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-violet-200 hover:bg-violet-700">Update Genre</button>
            </div>
        </form>
    </div>
</div>

    <script>
    /**
     * Standardized initialization
     */
    document.addEventListener('DOMContentLoaded', () => {
        // Auto-hide Flash Message with a smooth fade
        const flash = document.getElementById('flash-message');
        if (flash) {
            setTimeout(() => {
                flash.style.opacity = '0';
                flash.style.transition = 'opacity 0.5s ease';
                setTimeout(() => flash.remove(), 500);
            }, 4000);
        }
    });

    /**
     * Edit Genre Handler
     * Accepts the full genre object and an optional custom route
     */
    function editGenre(genre, updateUrl) {
    const form = document.getElementById('editGenreForm');
    form.action = updateUrl;

    // We store the URL in a hidden way so if validation fails, 
    // we can re-inject it into the form action
    fetch(`/save-edit-url?url=${encodeURIComponent(updateUrl)}`); 

    // Populate fields
    document.getElementById('edit_name').value = genre.name || '';
    document.getElementById('edit_description').value = genre.description || '';

    // Open Modal
    const modal = document.getElementById('editGenreModal');
    modal.classList.replace('hidden', 'flex');
}

    function closeGenreModal() {
        const modal = document.getElementById('editGenreModal');
        if (modal) {
            modal.classList.replace('flex', 'hidden');
        }
    }


</script>
</x-layouts.app>
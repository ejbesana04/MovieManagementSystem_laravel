<x-layouts.app :title="__('Movie Trash')">
    
    <div class="flex h-full w-full flex-1 flex-col gap-8 p-6 lg:p-10 font-sans bg-slate-50/50 relative">

        {{-- Flash Message (Floating Toast Style) --}}
        @if(session('success'))
            <div id="flash-message" class="fixed top-6 right-6 z-[100] rounded-xl bg-emerald-500 text-white px-4 py-3 shadow-xl flex items-center gap-2 animate-in slide-in-from-right-5 transition-all duration-500">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Header Area with Warning Stripe Pattern --}}
        <div class="relative rounded-2xl bg-white p-6 shadow-sm border border-slate-100 overflow-hidden">
            {{-- Stripes --}}
            <div class="absolute top-0 inset-x-0 h-1 bg-[repeating-linear-gradient(45deg,#fee2e2,#fee2e2_10px,#fff_10px,#fff_20px)]"></div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pt-2">
                <div class="flex items-center gap-5">
                    <div class="h-16 w-16 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center shadow-inner border border-red-100">
                        <i data-lucide="trash-2" class="h-8 w-8"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900">Recycle Bin</h1>
                        <p class="text-slate-500 text-sm font-medium mt-1">
                            Items in trash are not visible in the library. <br class="hidden md:block">Restore them to make them active again.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Deleted Items</span>
                        <span class="block text-2xl font-black text-slate-800">{{ $movies->count() }}</span>
                    </div>
                    <div class="h-10 w-px bg-slate-200 hidden md:block"></div>
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-2 px-5 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm font-bold text-slate-600 hover:bg-white hover:shadow-md transition-all">
                        <i data-lucide="arrow-left" class="h-4 w-4 group-hover:-translate-x-1 transition-transform"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        {{-- Content Table --}}
        <div class="rounded-3xl bg-white shadow-sm border border-slate-100 overflow-hidden min-h-[500px] flex flex-col">
            @if($movies->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-emerald-100 rounded-full blur-xl opacity-50"></div>
                        <div class="relative bg-emerald-50 p-6 rounded-full ring-8 ring-emerald-50/30">
                            <i data-lucide="sparkles" class="h-10 w-10 text-emerald-500"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-slate-800">Trash is Empty!</h3>
                    <p class="text-slate-500 mt-2 max-w-xs mx-auto font-medium">Your library is clean. Deleted movies will appear here for safe keeping.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-red-100 bg-red-50/30">
                                <th class="px-8 py-5 text-xs font-black text-red-900/50 uppercase tracking-wider">Movie Details</th>
                                <th class="px-6 py-5 text-xs font-black text-red-900/50 uppercase tracking-wider">Deleted Date</th>
                                <th class="px-8 py-5 text-xs font-black text-red-900/50 uppercase tracking-wider text-right">Recovery Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($movies as $movie)
                                <tr class="group hover:bg-red-50/10 transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-5">
                                            <div class="h-14 w-10 rounded-lg bg-slate-200 overflow-hidden shadow-sm relative grayscale group-hover:grayscale-0 transition-all">
                                                @if($movie->photo)
                                                    <img src="{{ $movie->photo ? Storage::url($movie->photo) : asset('images/placeholder.jpg') }}" 
                                                    class="h-full w-full object-cover"
                                                    alt="{{ $movie->title }}">
                                                @endif
                                                <div class="absolute inset-0 bg-red-500/10 group-hover:bg-transparent"></div>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-base">{{ $movie->title }}</p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-xs font-bold text-white bg-slate-400 px-2 py-0.5 rounded-full">{{ $movie->genre->name ?? 'N/A' }}</span>
                                                    <span class="text-xs text-slate-400 font-mono">{{ $movie->release_year }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-2 text-sm text-slate-500 font-medium bg-slate-50 w-fit px-3 py-1 rounded-lg border border-slate-100">
                                            <i data-lucide="calendar-clock" class="h-4 w-4 text-slate-400"></i>
                                            {{ $movie->deleted_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex justify-end gap-3 opacity-80 group-hover:opacity-100 transition-opacity">
                                            <form method="POST" action="{{ route('movies.restore', $movie->id) }}">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-2.5 text-xs font-bold text-emerald-700 transition-all hover:bg-emerald-500 hover:text-white hover:shadow-lg hover:shadow-emerald-200">
                                                    <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i> Restore
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('movies.force-delete', $movie->id) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('WARNING: This will permanently remove the file. Continue?')" 
                                                    class="flex items-center gap-2 rounded-xl border border-red-100 bg-white px-4 py-2.5 text-xs font-bold text-red-500 transition-all hover:bg-red-500 hover:text-white hover:shadow-lg hover:shadow-red-200">
                                                    <i data-lucide="x" class="h-3.5 w-3.5"></i> Delete
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

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        // Flash Message Logic - Toast Fade Out
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.opacity = '0';
                setTimeout(() => flashMessage.remove(), 500); 
            }, 4000); 
        }
    </script>
</x-layouts.app>
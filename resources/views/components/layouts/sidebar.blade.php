<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    @include('partials.head')
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 20px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Subtle Pattern for Sidebar Header */
        .bg-grid-pattern {
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 font-sans antialiased selection:bg-blue-100 selection:text-blue-900">
    
    <div class="flex h-screen w-full overflow-hidden">
        
        {{-- Sidebar --}}
        <flux:sidebar sticky stashable class="h-full border-r border-slate-200/60 bg-white/80 backdrop-blur-xl shadow-[4px_0_24px_-12px_rgba(0,0,0,0.1)] transition-all duration-300 flex flex-col shrink-0 z-20">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            {{-- Brand / Logo Section with subtle pattern --}}
            <div class="relative flex w-full flex-col items-center justify-center pt-10 pb-8 border-b border-dashed border-slate-200 bg-grid-pattern">
                <a href="{{ route('dashboard') }}" 
                   class="relative z-10 flex items-center justify-center transition-transform duration-300 hover:scale-105 hover:drop-shadow-lg" 
                   wire:navigate>
                    <img src="{{ asset('images/mms-logo.png') }}" 
                         alt="MMS Logo" 
                         class="h-28 w-auto object-contain lg:h-32 xl:h-36 mx-auto">
                </a>
                {{-- Decorative gradient blob behind logo --}}
                <div class="absolute inset-0 z-0 flex items-center justify-center opacity-40">
                    <div class="h-24 w-24 rounded-full bg-blue-100 blur-2xl"></div>
                </div>
            </div>

            {{-- Navigation --}}
            <flux:navlist variant="outline" class="px-3 pt-6 flex-1 space-y-1.5">
                <div class="px-4 mb-3">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400/80">Menu</span>
                </div>

                <flux:navlist.item 
                    icon="squares-2x2" 
                    :href="route('dashboard')" 
                    :current="request()->routeIs('dashboard')" 
                    class="rounded-xl font-medium transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 data-[current]:bg-gradient-to-r data-[current]:from-blue-600 data-[current]:to-blue-500 data-[current]:text-white data-[current]:shadow-md data-[current]:shadow-blue-200"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navlist.item>

                <flux:navlist.item
                    icon="tag"
                    :href="route('genres.index')"
                    :current="request()->routeIs('genres.*')"
                    class="rounded-xl font-medium transition-all duration-200 hover:bg-violet-50 hover:text-violet-600 data-[current]:bg-gradient-to-r data-[current]:from-violet-600 data-[current]:to-violet-500 data-[current]:text-white data-[current]:shadow-md data-[current]:shadow-violet-200"
                    wire:navigate>
                    {{ __('Genres') }}
                </flux:navlist.item>

                <flux:navlist.item
                    icon="trash"
                    :href="route('movies.trash')"
                    :current="request()->routeIs('movies.trash')"
                    class="rounded-xl font-medium transition-all duration-200 hover:bg-red-50 hover:text-red-600 data-[current]:bg-gradient-to-r data-[current]:from-red-500 data-[current]:to-pink-500 data-[current]:text-white data-[current]:shadow-md data-[current]:shadow-red-200"
                    wire:navigate>
                    {{ __('Trash') }}
                </flux:navlist.item>
            </flux:navlist>

            {{-- Profile --}}
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                    <button class="flex w-full items-center gap-3 rounded-xl p-2.5 transition-all hover:bg-white hover:shadow-sm border border-transparent hover:border-slate-200">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 text-sm font-bold text-white shadow-lg shadow-slate-300">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="flex-1 text-left">
                            <p class="text-sm font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-wide text-blue-600">Administrator</p>
                        </div>
                        <flux:icon name="chevron-up-down" variant="micro" class="text-slate-400" />
                    </button>

                    <flux:menu class="w-[240px] rounded-2xl border-slate-200 p-2 shadow-xl ring-1 ring-black/5">
                        <flux:menu.item :href="route('profile.edit')" icon="user" wire:navigate>{{ __('My Profile') }}</flux:menu.item>
                        <flux:menu.item :href="route('profile.edit')" icon="cog-6-tooth" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-600 hover:bg-red-50">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </flux:sidebar>

        {{-- Main Content --}}
        <main class="flex-1 h-full overflow-y-auto bg-slate-50 relative scroll-smooth">
            {{-- Mobile Header --}}
            <flux:header class="lg:hidden border-b border-slate-200 bg-white/80 backdrop-blur-md sticky top-0 px-4 z-30">
                <flux:sidebar.toggle icon="bars-3" variant="ghost" class="text-slate-600" />
                <flux:spacer />
                <div class="h-8 w-8 rounded-full bg-slate-900 flex items-center justify-center text-[10px] text-white font-bold">
                    {{ auth()->user()->initials() }}
                </div>
            </flux:header>

            <div class="min-h-full pb-12">
                {{ $slot }}
            </div>
        </main>

    </div>

    @fluxScripts
</body>
</html>
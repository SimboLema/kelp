<header class="h-20 glass border-b border-slate-200 px-6 md:px-10 flex items-center justify-between sticky top-0 z-10">
    {{-- Dynamic Header Title --}}
    <div>
        <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
            @yield('header_title', 'Overview Dashboard')
        </h2>
        <p class="text-xs text-slate-400 font-medium mt-0.5">
            @yield('header_subtitle', 'Welcome back, Admin')
        </p>
    </div>

    {{-- Status Badge --}}
    <div class="flex items-center gap-2.5 px-4 py-1.5 bg-white border border-slate-200 rounded-full shadow-sm">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
        </span>
        <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">System Live</span>
    </div>
</header>

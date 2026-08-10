<aside class="hidden md:flex flex-col w-72 bg-white border-r border-slate-200 h-screen sticky top-0 shadow-sm z-20">
    {{-- Brand Logo --}}
    <div class="p-8 mb-2">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="KELP Logo" width="120" class="hover:opacity-80 transition cursor-pointer">
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3.5 rounded-r-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'sidebar-item-active shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            Dashboard
        </a>

        {{-- Businesses Dropdown --}}
        <div class="relative">
            <button @click="openVendor = !openVendor"
                class="w-full flex items-center justify-between px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all font-medium group">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2z"/><path d="M22 2h-10v10h10V2z"/><path d="M12 12H2v10h10V12z"/><path d="M22 12h-10v10h10V12z"/></svg>
                    <span>Businesses</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-300" :class="openVendor ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>

            <div x-show="openVendor" x-cloak x-transition class="mt-1 ml-4 pl-4 border-l-2 border-slate-100 space-y-1">
                <a href="{{ route('admin.businessOwner.index') }}"
                   class="block px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.businessOwner.index') ? 'text-orange-600 font-bold' : 'text-slate-500 hover:text-orange-600' }}">
                    List
                </a>
            </div>
        </div>

        {{-- Categories --}}
        <a href="{{ route('admin.categories') }}"
           class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.categories') ? 'sidebar-item-active shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m16 6 4 14H4L8 6"/><path d="M12 6V2"/><path d="M9 2h6"/></svg>
            Categories
        </a>

        {{-- Agents --}}
        <a href="{{ route('admin.agents') }}"
           class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.agents') ? 'sidebar-item-active shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Agents
        </a>

        {{-- Ipf --}}
        <a href="{{ route('admin.ipf') }}"
           class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all {{ request()->routeIs('admin.agents') ? 'sidebar-item-active shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50 font-medium' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            IPF
        </a>

    </nav>

    {{-- Logout Section --}}
    <div class="p-6 border-t border-slate-100">
        <form action="#" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-orange-500 text-white font-semibold py-3 rounded-xl hover:bg-orange-600 transition-all shadow-md shadow-orange-200 active:scale-95">
                Logout
            </button>
        </form>
    </div>
</aside>

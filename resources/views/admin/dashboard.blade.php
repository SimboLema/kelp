<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316', // Primary Orange
                            600: '#ea580c', // Darker Orange
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js for interactive sidebar & dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">

        <!-- SIDEBAR -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0">

            <!-- Logo Header -->
            <div class="h-16 flex items-center px-6 border-b border-slate-100 justify-between">
                <div class="flex items-center gap-3">
                    <!-- Logo -->
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain">
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Main Menu</p>

                <!-- Active Link -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-brand-50 text-brand-600 font-medium transition-all">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <!-- Standard Links -->
                <a href="{{ route('admin.agents') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Agents
                </a>

                <a href="{{ route('admin.businessOwner.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Business List
                </a>

                <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Business Categories
                </a>

                <a href="{{ route('admin.ipf.plans.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    IPF
                </a>

                <a href="{{ route('admin.ipf.accounts.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    IPF Accounts
                </a>

                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 mt-8 mb-3">Settings</p>

                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                    Configuration
                </a>
            </nav>

            <!-- User Profile Footer -->
            <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()?->name ?? 'Admin User') }}&background=ffedd5&color=ea580c" class="w-9 h-9 rounded-full" alt="{{ Auth::user()?->name ?? 'Admin' }}">
                    <div class="text-xs">
                        <p class="font-semibold text-slate-800">{{ Auth::user()?->name ?? 'Admin User' }}</p>
                        <p class="text-slate-400">{{ Auth::user()?->email ?? 'admin@company.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- Top Navbar -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden text-slate-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-xl font-bold text-slate-800">Overview</h1>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-brand-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <!-- Main Dashboard Body -->
            <main class="flex-1 overflow-y-auto p-6 space-y-6">

                <!-- STATS CARDS GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- Total Agents -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-5 rounded-2xl shadow-lg shadow-orange-500/20 text-white flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold tracking-wider text-orange-100 uppercase">
                                Total Agents
                            </span>

                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857
                                        M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                        M7 20H2v-2a3 3 0 015.356-1.857
                                        M7 20v-2c0-.656.126-1.283.356-1.857
                                        m0 0a5.002 5.002 0 019.288 0
                                        M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h2 class="text-3xl font-extrabold text-white tracking-tight">
                                {{ number_format($totalAgents) }}
                            </h2>

                            <div class="flex items-center gap-1 mt-2 text-xs font-medium text-orange-100">
                                <span>Registered insurance agents</span>
                            </div>
                        </div>
                    </div>


                    <!-- Total Businesses -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-5 rounded-2xl shadow-lg shadow-orange-500/20 text-white flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold tracking-wider text-orange-100 uppercase">
                                Total Businesses
                            </span>

                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16
                                        M9 21v-4h6v4M9 7h2m2 0h2M9 11h2m2 0h2"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h2 class="text-3xl font-extrabold text-white tracking-tight">
                                {{ number_format($totalBusinesses) }}
                            </h2>

                            <div class="flex items-center gap-1 mt-2 text-xs font-medium text-orange-100">
                                <span>Registered businesses</span>
                            </div>
                        </div>
                    </div>


                    <!-- Total Insurance Orders -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-5 rounded-2xl shadow-lg shadow-orange-500/20 text-white flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold tracking-wider text-orange-100 uppercase">
                                Insurance Orders
                            </span>

                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2z
                                        M13 3v4h4"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h2 class="text-3xl font-extrabold text-white tracking-tight">
                                {{ number_format($totalInsuranceOrders) }}
                            </h2>

                            <div class="flex items-center gap-1 mt-2 text-xs font-medium text-orange-100">
                                <span>Total insurance orders</span>
                            </div>
                        </div>
                    </div>


                    <!-- Total Reviews -->
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-5 rounded-2xl shadow-lg shadow-orange-500/20 text-white flex flex-col justify-between relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold tracking-wider text-orange-100 uppercase">
                                Total Reviews
                            </span>

                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0
                                        l1.286 3.96a1 1 0 00.95.69h4.163c.969 0 1.371 1.24.588 1.81
                                        l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.96
                                        c.3.922-.755 1.688-1.54 1.95-.588l-3.368-2.446
                                        a1 1 0 00-1.176 0l-3.368 2.446
                                        c-.795.578-1.85-.307-1.54-1.228l1.287-3.96
                                        a1 1 0 00-.364-1.118L2.98 9.387
                                        c-.783-.57-.38-1.81.588-1.81h4.163
                                        a1 1 0 00.95-.69l1.286-3.96z"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h2 class="text-3xl font-extrabold text-white tracking-tight">
                                {{ number_format($totalReviews) }}
                            </h2>

                            <div class="flex items-center gap-1 mt-2 text-xs font-medium text-orange-100">
                                <span>Customer reviews</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- TABLE SECTION -->
                

            </main>
        </div>

    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Business Owner Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .sidebar-item-active {
            border-left: 4px solid #f97316;
            background: #fff7ed;
            color: #ea580c;
            font-weight: 700;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full antialiased font-sans text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="min-h-full flex flex-col md:flex-row">

        <!-- Mobile Header Navigation -->
        <header class="md:hidden bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                    B
                </div>
                <span class="font-bold text-slate-900 text-lg">{{ Auth::user()->name }}</span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-slate-600 hover:text-slate-900 focus:outline-none">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </header>

        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-cloak
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden transition-opacity">
        </div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 ease-in-out md:translate-x-0">

            <!-- Logo Section -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-orange-600 to-amber-500 flex items-center justify-center text-white font-bold text-xl shadow-md shadow-orange-500/20">
                        B
                    </div>
                    <div>
                        <h1 class="font-bold text-slate-900 text-base leading-tight">{{Auth::user()->name  }}</h1>
                        <span class="text-xs text-slate-400 font-medium">Owner Console</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Main Navigation Links -->
            <nav class="flex-1 py-6 space-y-1">
                <a href="{{ route('businessOwner.dashboard') }}"
                   class="flex items-center space-x-3 px-6 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('businessOwner.dashboard') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="text-sm">Dashboard</span>
                </a>

                <a href="{{ route('business.reviews') }}"
                   class="flex items-center space-x-3 px-6 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors {{ request()->routeIs('business.reviews') ? 'sidebar-item-active' : '' }}">
                    <i data-lucide="star" class="w-5 h-5"></i>
                    <span class="text-sm">Reviews</span>
                </a>
            </nav>

            <!-- User Footer Profile -->
            <div class="p-4 border-t border-slate-100">
                <div class="flex items-center space-x-3 p-2 rounded-lg bg-slate-50">
                    <div class="w-9 h-9 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-semibold text-sm">
                        BO
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-900 truncate">Business Owner</p>
                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top App Bar -->
            <header class="hidden md:flex glass sticky top-0 z-20 border-b border-slate-200/80 px-8 py-4 items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">@yield('title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-500">Welcome back to your business overview.</p>
                </div>

                <div class="flex items-center space-x-4">
                    <button class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 transition-colors relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-orange-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <!-- Page Dynamic Content -->
            <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
                @hasSection('content')
                    @yield('content')
                @else
                    <!-- Default Dashboard View -->
                    <div class="space-y-6">

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <!-- Total Reviews Card -->
                            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Reviews</p>
                                    <h3 class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalReviews ?? 0 }}</h3>
                                    <span class="inline-flex items-center text-xs font-medium text-emerald-600 mt-2">
                                        <i data-lucide="trending-up" class="w-3.5 h-3.5 mr-1"></i> +12% this month
                                    </span>
                                </div>
                                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-inner">
                                    <i data-lucide="star" class="w-7 h-7"></i>
                                </div>
                            </div>

                            <!-- Placeholder Stat Card 2 -->
                            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Average Rating</p>
                                    <h3 class="text-3xl font-extrabold text-slate-900 mt-2">4.8</h3>
                                    <span class="inline-flex items-center text-xs font-medium text-emerald-600 mt-2">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 mr-1"></i> Excellent score
                                    </span>
                                </div>
                                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shadow-inner">
                                    <i data-lucide="award" class="w-7 h-7"></i>
                                </div>
                            </div>

                            <!-- Placeholder Stat Card 3 -->
                            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Response Rate</p>
                                    <h3 class="text-3xl font-extrabold text-slate-900 mt-2">98%</h3>
                                    <span class="inline-flex items-center text-xs font-medium text-orange-600 mt-2">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 mr-1"></i> ~2h avg time
                                    </span>
                                </div>
                                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-inner">
                                    <i data-lucide="message-square" class="w-7 h-7"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Content Placeholder Section -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 min-h-[300px] flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                <i data-lucide="layers" class="w-6 h-6"></i>
                            </div>
                            <h4 class="text-base font-semibold text-slate-700">Main Content Area</h4>
                            <p class="text-sm text-slate-400 max-w-sm mt-1">Extend this layout by yielding <code class="text-orange-600 font-mono">@section('content')</code> in your Blade views.</p>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>

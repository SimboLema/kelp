<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Order #{{ $order->id }} - Admin Dashboard</title>
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
    <!-- Alpine.js -->
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
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain">
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Main Menu</p>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

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

                <!-- Active Link -->
                <a href="{{ route('admin.insurance-orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-brand-50 text-brand-600 font-medium transition-all">
                    <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l4 4v12a2 2 0 01-2 2zM13 3v4h4"/></svg>
                    Insurance Orders
                </a>

                <a href="{{ route('admin.ipf.plans.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    IPF
                </a>

                <a href="{{ route('admin.ipf.accounts.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium transition-all">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
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
                    <a href="{{ route('admin.insurance-orders.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                    <h1 class="text-xl font-bold text-slate-800">Order Details #{{ $order->id }}</h1>
                </div>

                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        @if(($order->status ?? 'pending') === 'approved' || ($order->status ?? '') === 'completed') bg-emerald-100 text-emerald-700
                        @elseif(($order->status ?? 'pending') === 'rejected' || ($order->status ?? '') === 'cancelled') bg-rose-100 text-rose-700
                        @else bg-amber-100 text-amber-700 @endif">
                        {{ ucfirst($order->status ?? 'Pending') }}
                    </span>
                </div>
            </header>

            <!-- Main Body -->
            <main class="flex-1 overflow-y-auto p-6 space-y-6">

                <!-- TOP SUMMARY HEADER CARD -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-2xl shadow-lg shadow-orange-500/20 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
                    <div>
                        <span class="text-xs font-bold tracking-wider text-orange-100 uppercase">Insurance Order</span>
                        <h2 class="text-3xl font-extrabold tracking-tight mt-1">
                            TSH {{ number_format($order->amount ?? $order->total_amount ?? 0, 2) }}
                        </h2>
                        <p class="text-xs text-orange-100 mt-1">
                            Created on {{ $order->created_at ? $order->created_at->format('M d, Y • h:i A') : 'N/A' }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.insurance-orders.index') }}" class="px-4 py-2 bg-white/20 backdrop-blur-md hover:bg-white/30 text-white rounded-xl text-xs font-medium transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0"/></svg>
                            Back to Orders
                        </a>
                    </div>
                </div>

                <!-- 2-COLUMN GRID DETAILS -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- LEFT COLUMN: PRIMARY DETAILS (2 COLS) -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Insurance & Policy Details -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Policy & Product Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <span class="text-xs text-slate-400 block font-medium">Insurance Name</span>
                                    <span class="text-sm font-semibold text-slate-800">{{ $order->insurance?->name ?? $order->insurance?->title ?? 'N/A' }}</span>
                                </div>

                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <span class="text-xs text-slate-400 block font-medium">Insurer / Provider</span>
                                    <span class="text-sm font-semibold text-slate-800">{{ $order->insurer?->company_name ?? $order->insurer?->name ?? 'N/A' }}</span>
                                </div>

                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <span class="text-xs text-slate-400 block font-medium">Product</span>
                                    <span class="text-sm font-semibold text-slate-800">{{ $order->product?->name ?? 'N/A' }}</span>
                                </div>

                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <span class="text-xs text-slate-400 block font-medium">Coverage Type</span>
                                    <span class="text-sm font-semibold text-slate-800">{{ $order->coverage?->name ?? $order->coverage?->type ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- IPF Account Details -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Insurance Premium Financing (IPF)
                            </h3>

                            @if($order->ipfAccount)
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                        <span class="text-xs text-slate-400 block font-medium">Account Number</span>
                                        <span class="text-sm font-semibold text-slate-800">{{ $order->ipfAccount->account_number ?? $order->ipfAccount->id }}</span>
                                    </div>

                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                        <span class="text-xs text-slate-400 block font-medium">Total Financed</span>
                                        <span class="text-sm font-semibold text-slate-800">TSH {{ number_format($order->ipfAccount->total_amount ?? 0, 2) }}</span>
                                    </div>

                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                        <span class="text-xs text-slate-400 block font-medium">Account Status</span>
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-brand-50 text-brand-600 inline-block mt-0.5">
                                            {{ ucfirst($order->ipfAccount->status ?? 'Active') }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="p-4 bg-slate-50 rounded-xl text-xs text-slate-500 text-center">
                                    No IPF Account linked to this order.
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- RIGHT COLUMN: USER & SUMMARY (1 COL) -->
                    <div class="space-y-6">

                        <!-- Customer Details Card -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Customer Details
                            </h3>

                            <div class="flex items-center gap-3 mb-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user?->name ?? 'Customer') }}&background=ffedd5&color=ea580c" class="w-12 h-12 rounded-full border border-orange-200" alt="Avatar">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">{{ $order->user?->name ?? 'N/A' }}</h4>
                                    <p class="text-xs text-slate-400">User ID: #{{ $order->user?->id ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="space-y-3 pt-2 text-xs border-t border-slate-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Email</span>
                                    <span class="font-semibold text-slate-700">{{ $order->user?->email ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Phone</span>
                                    <span class="font-semibold text-slate-700">{{ $order->user?->phone ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- System Reference Card -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">Meta Data</h3>

                            <div class="space-y-3 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Order Reference</span>
                                    <span class="font-mono font-semibold text-slate-700">#{{ $order->id }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Created At</span>
                                    <span class="font-semibold text-slate-700">{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Last Updated</span>
                                    <span class="font-semibold text-slate-700">{{ $order->updated_at ? $order->updated_at->format('Y-m-d H:i') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </main>
        </div>

    </div>

</body>
</html>

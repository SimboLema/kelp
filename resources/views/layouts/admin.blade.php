<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KELP | Admin Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
        .sidebar-item-active { border-left: 4px solid #f97316; background: #fff7ed; color: #ea580c; font-weight: 700; }
        .stat-card:hover { transform: translateY(-4px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>

<body class="bg-[#F1F5F9] text-slate-900" x-data="{ sidebarOpen: false, openVendor: {{ request()->routeIs('admin.businessOwner.*') ? 'true' : 'false' }} }">

<div class="min-h-screen flex">
    {{-- Sidebar Partial --}}
    @include('partials.sidebar')

    {{-- Main Content Container --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        {{-- Header Partial --}}
        @include('partials.header')

        {{-- Dynamic Main Content --}}
        <main class="p-6 md:p-10 space-y-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>

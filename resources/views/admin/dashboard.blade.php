<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KELP | Admin Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .sidebar-item-active { border-left: 4px solid #f97316; background: #fff7ed; color: #ea580c; font-weight: 700; }
        .stat-card:hover { transform: translateY(-4px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-[#F1F5F9] text-slate-900" x-data="{ openVendor: false }">

<div class="min-h-screen flex">
    <aside class="hidden md:flex flex-col w-72 bg-white border-r border-slate-200 h-screen sticky top-0 shadow-sm">
        <div class="p-8 mb-4">
            <img src="{{ asset('assets/images/logo.png') }}" width="120" class="hover:opacity-80 transition cursor-pointer">
        </div>

        <nav class="flex-1 px-4 space-y-1">
        

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 sidebar-item-active rounded-r-xl transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Dashboard
            </a>

            <div class="relative">
                <button @click="openVendor = !openVendor"
                    class="w-full flex items-center justify-between px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all group">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2z"/><path d="M22 2h-10v10h10V2z"/><path d="M12 12H2v10h10V12z"/><path d="M22 12h-10v10h10V12z"/></svg>
                        <span class="font-medium">Businesses</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-300" :class="openVendor ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div x-show="openVendor" x-cloak x-transition class="mt-1 ml-4 pl-4 border-l-2 border-slate-100 space-y-1">
                    <a href="{{ route('admin.businessOwner.index') }}" class="block px-4 py-2 text-sm text-slate-500 hover:text-orange-600 font-medium transition-colors">List</a>

                </div>
            </div>
            <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m16 6 4 14H4L8 6"/><path d="M12 6V2"/><path d="M9 2h6"/></svg>
                Categories
            </a>
            <a href="{{ route('admin.agents') }}" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m16 6 4 14H4L8 6"/><path d="M12 6V2"/><path d="M9 2h6"/></svg>
                Agents
            </a>
        </nav>

        <div class="p-6 border-t border-slate-100">
            <form action="#" method="POST">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 bg-orange-500 text-white font-semibold py-3 rounded-xl hover:bg-orange-600 transition-all shadow-lg active:scale-95">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="h-20 glass border-b border-slate-200 px-10 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Overview Dashboard</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Welcome back, Admin</p>
            </div>
            <div class="flex items-center gap-2.5 px-4 py-1.5 bg-white border border-slate-200 rounded-full shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">System Live</span>
            </div>
        </header>

        <main class="p-10 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php $stats = [['label' => 'Trade Volume', 'val' => '0'], ['label' => 'Active Policies', 'val' => '0'], ['label' => 'Businesses', 'val' => $restaurantCount ?? 0]]; @endphp
                @foreach($stats as $stat)
                <div class="bg-orange-50 p-6 rounded-[2rem] border-2 border-orange-500 shadow-xl transition-all hover:-translate-y-1">
                    <p class="text-[10px] font-bold text-white tracking-widest uppercase px-3 py-1 bg-orange-500 rounded-lg w-fit">{{ $stat['label'] }}</p>
                    <h3 class="text-3xl font-black text-orange-500 mt-3">{{ $stat['val'] }}</h3>
                </div>
                @endforeach
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-xl">
                <h4 class="text-xl font-extrabold text-slate-800 tracking-tight mb-8">Growth Analytics</h4>
                <div class="h-80 w-full">
                    <canvas id="adminChart"></canvas>
                </div>
            </div>
        </main>
    </div>
</div>

<script type="module">
    import Chart from 'chart.js/auto';

    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('adminChart');
        if (!ctx) return;

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(249, 115, 22, 0.2)');
        gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                    label: "Restaurants",
                    data: [3, 5, 8, 12, 15, 20],
                    borderColor: '#f97316',
                    borderWidth: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#f97316',
                    pointRadius: 6,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                }
            }
        });
    });
</script>

</body>
</html>

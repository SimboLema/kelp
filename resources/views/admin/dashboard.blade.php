@extends('layouts.admin')

@section('title', 'Dashboard | KELP')
@section('header_title', 'Overview Dashboard')
@section('header_subtitle', 'Welcome back, Admin')

@section('content')
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $stats = [
                ['label' => 'Trade Volume', 'val' => '$0.00'],
                ['label' => 'Active Policies', 'val' => '0'],
                ['label' => 'Businesses', 'val' => $restaurantCount ?? 0]
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-gradient-to-br from-orange-50 to-white p-6 rounded-[2rem] border-2 border-orange-500 shadow-xl shadow-orange-500/5 transition-all hover:-translate-y-1">
            <p class="text-[10px] font-bold text-white tracking-widest uppercase px-3 py-1 bg-orange-500 rounded-lg w-fit">
                {{ $stat['label'] }}
            </p>
            <h3 class="text-3xl font-black text-orange-500 mt-3">{{ $stat['val'] }}</h3>
        </div>
        @endforeach
    </div>

    {{-- Chart Section --}}
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50">
        <h4 class="text-xl font-extrabold text-slate-800 tracking-tight mb-8">Growth Analytics</h4>
        <div class="h-80 w-full">
            <canvas id="adminChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script type="module">
    import Chart from 'chart.js/auto';

    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('adminChart');
        if (!ctx) return;

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(249, 115, 22, 0.25)');
        gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                    label: "Businesses",
                    data: [3, 5, 8, 12, 15, 20],
                    borderColor: '#f97316',
                    borderWidth: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#f97316',
                    pointBorderWidth: 3,
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
@endpush

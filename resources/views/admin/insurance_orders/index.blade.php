<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Orders - Admin Portal</title>

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
                            500: '#f97316',
                            600: '#ea580c',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen">

@php
    $statusColors = [
        'pending'    => 'bg-amber-50 text-amber-700 border-amber-200',
        'approved'   => 'bg-brand-50 text-brand-600 border-brand-200',
        'active'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'completed'  => 'bg-blue-50 text-blue-700 border-blue-200',
        'rejected'   => 'bg-rose-50 text-rose-700 border-rose-200',
        'cancelled'  => 'bg-slate-100 text-slate-600 border-slate-200',
    ];
@endphp

<!-- TOP NAVIGATION / HEADER -->
<header class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        <!-- Back to Dashboard Button -->
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100/80 hover:bg-slate-200 hover:text-slate-900 transition-all border border-slate-200/80">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>

        <!-- Header Branding or Title -->
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Insurance Orders</span>
        </div>
    </div>
</header>

<!-- MAIN CONTAINER -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- PAGE TITLE & TOP ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Insurance Orders</h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage, verify, and review all customer insurance applications.</p>
        </div>
    </div>

    <!-- FLASH SUCCESS ALERT -->
    @if (session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- FILTER FORM CARD -->
    <form method="GET" action="{{ route('admin.insurance-orders.index') }}" class="bg-white shadow-sm rounded-2xl border border-slate-200/80 p-5 mb-6 flex flex-wrap gap-4 items-end">

        <!-- Search Input -->
        <div class="flex-1 min-w-[220px]">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Ref no., Customer, vehicle reg..."
                   class="w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500/20 placeholder-slate-400 bg-slate-50/50">
        </div>

        <!-- Status Filter -->
        <div class="w-full sm:w-40">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Status</label>
            <select name="status" class="w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500/20 bg-slate-50/50">
                <option value="">All Statuses</option>
                @foreach (['pending', 'approved', 'active', 'completed', 'rejected', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Payment Mode Filter -->
        <div class="w-full sm:w-44">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Payment Mode</label>
            <select name="payment_mode" class="w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500/20 bg-slate-50/50">
                <option value="">All Modes</option>
                <option value="upfront" @selected(request('payment_mode') === 'upfront')>Upfront</option>
                <option value="ipf" @selected(request('payment_mode') === 'ipf')>IPF Financing</option>
            </select>
        </div>

        <!-- Date Filter -->
        <div class="w-full sm:w-40">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Date</label>
            <input type="date" name="date" value="{{ request('date') }}"
                   class="w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500/20 bg-slate-50/50 text-slate-600">
        </div>

        <!-- Filter Action Buttons -->
        <div class="flex items-center gap-2 pt-2 sm:pt-0">
            <button type="submit"
                    class="px-5 py-2.5 bg-brand-500 text-white text-sm font-semibold rounded-xl hover:bg-brand-600 transition-all shadow-md shadow-brand-500/20">
                Filter
            </button>

            @if (request()->hasAny(['search', 'status', 'payment_mode', 'date']))
                <a href="{{ route('admin.insurance-orders.index') }}"
                   class="px-3.5 py-2.5 text-sm text-slate-500 hover:text-slate-700 font-medium hover:bg-slate-100 rounded-xl transition-all">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- TABLE CARD -->
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/80">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/70 text-slate-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-bold">Reference & Vehicle</th>
                        <th class="px-5 py-3.5 text-left font-bold">Customer</th>
                        <th class="px-5 py-3.5 text-left font-bold">Cover & Company</th>
                        <th class="px-5 py-3.5 text-right font-bold">Premium Amount</th>
                        <th class="px-5 py-3.5 text-center font-bold">Payment Mode</th>
                        <th class="px-5 py-3.5 text-left font-bold">Status</th>
                        <th class="px-5 py-3.5 text-left font-bold">Date</th>

                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Reference & Vehicle -->
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">{{ $order->reference_no ?? 'N/A' }}</div>
                                <div class="text-slate-400 text-xs font-mono">{{ $order->registration_number ?? '—' }}</div>
                            </td>

                            <!-- Customer -->
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">{{ $order->user->name ?? '—' }}</div>
                                <div class="text-slate-400 text-xs">{{ $order->user->phone_number ?? '' }}</div>
                            </td>

                            <!-- Cover & Company -->
                            <td class="px-5 py-4 text-slate-700">
                                <div class="font-medium text-slate-800">{{ $order->product_name ?? '—' }}</div>
                                <div class="text-slate-400 text-xs">{{ $order->insurer_name ?? '—' }}</div>
                            </td>

                            <!-- Premium Amount -->
                            <td class="px-5 py-4 text-right font-semibold text-slate-800">
                                TZS {{ number_format($order->premium ?? 0, 2) }}
                            </td>

                            <!-- Payment Mode Badge -->
                            <td class="px-5 py-4 text-center">
                                @if(($order->payment_mode ?? '') === 'ipf')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-600 border border-brand-200">
                                        IPF Financed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        Cash
                                    </span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    {{ ucfirst($order->status ?? 'pending') }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-5 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $order->created_at ? $order->created_at->format('M d, Y') : '—' }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="font-medium text-slate-600">No insurance orders found</p>
                                    <p class="text-xs text-slate-400 mt-1">Try adjusting your filters or search terms.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>

</main>

</body>
</html>

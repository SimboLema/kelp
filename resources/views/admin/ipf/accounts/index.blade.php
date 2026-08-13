<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPF Accounts - Admin Portal</title>

    <!-- Tailwind CSS -->
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
        'pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
        'active'    => 'bg-brand-50 text-brand-600 border-brand-200',
        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'defaulted' => 'bg-rose-50 text-rose-700 border-rose-200',
        'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
    ];
@endphp

<!-- TOP NAVIGATION / HEADER -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        <!-- Back to Dashboard Button -->
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 transition-all border border-slate-200/80">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>

        <!-- Header Branding or Title -->
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">IPF Management</span>
        </div>
    </div>
</header>

<!-- MAIN CONTAINER -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- PAGE TITLE & TOP ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">IPF Accounts</h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage and track insurance premium financing accounts.</p>
        </div>
        <div>
            <a href="{{ route('admin.ipf.report') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 rounded-xl transition-all border border-brand-100">
                <span>View Report</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
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
    <form method="GET" class="bg-white shadow-sm rounded-2xl border border-slate-200/80 p-5 mb-6 flex flex-wrap gap-4 items-end">

        <div class="flex-1 min-w-[220px]">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Customer, phone, order ref, reg no."
                   class="w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500/20 placeholder-slate-400 bg-slate-50/50">
        </div>

        <div class="w-full sm:w-44">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Status</label>
            <select name="status" class="w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500/20 bg-slate-50/50">
                <option value="">All Statuses</option>
                @foreach (['pending', 'active', 'completed', 'defaulted', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-full sm:w-48">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Plan</label>
            <select name="ipf_plan_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500/20 bg-slate-50/50">
                <option value="">All Plans</option>
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}" @selected((string) request('ipf_plan_id') === (string) $plan->id)>
                        {{ $plan->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2 pt-2 sm:pt-0">
            <button type="submit"
                    class="px-5 py-2.5 bg-brand-500 text-white text-sm font-semibold rounded-xl hover:bg-brand-600 transition-all shadow-md shadow-brand-500/20">
                Filter Results
            </button>

            @if (request()->hasAny(['search', 'status', 'ipf_plan_id']))
                <a href="{{ route('admin.ipf.accounts.index') }}"
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
                        <th class="px-5 py-3.5 text-left font-bold">Customer</th>
                        <th class="px-5 py-3.5 text-left font-bold">Order Details</th>
                        <th class="px-5 py-3.5 text-left font-bold">Plan</th>
                        <th class="px-5 py-3.5 text-right font-bold">Financed</th>
                        <th class="px-5 py-3.5 text-right font-bold">Paid</th>
                        <th class="px-5 py-3.5 text-right font-bold">Remaining</th>
                        <th class="px-5 py-3.5 text-left font-bold w-44">Progress</th>
                        <th class="px-5 py-3.5 text-left font-bold">Status</th>
                        <th class="px-5 py-3.5 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($accounts as $account)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">{{ $account->user->name ?? '—' }}</div>
                                <div class="text-slate-400 text-xs">{{ $account->user->phone_number ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-700">
                                <div class="font-medium text-slate-800">{{ $account->order->reference_no ?? '—' }}</div>
                                <div class="text-slate-400 text-xs">{{ $account->order->registration_number ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600 font-medium">
                                {{ $account->plan->name ?? '—' }}
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-slate-800">
                                {{ number_format($account->financed_amount, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-emerald-600">
                                {{ number_format($account->total_paid, 2) }}
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-slate-800">
                                {{ number_format($account->remaining_amount, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-100">
                                    <div class="bg-brand-500 h-2 rounded-full transition-all duration-300" style="width: {{ min(100, $account->progress_percent) }}%"></div>
                                </div>
                                <div class="text-[11px] font-semibold text-slate-400 mt-1.5">{{ number_format($account->progress_percent, 1) }}%</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$account->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    {{ ucfirst($account->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.ipf.accounts.show', $account->id) }}"
                                   class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-500 hover:underline">
                                    <span>View</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="font-medium text-slate-600">No accounts found</p>
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
        {{ $accounts->links() }}
    </div>

</main>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPF Report - Admin Portal</title>
    
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
                            500: '#f97316', // Primary Orange Accent
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

<!-- TOP NAVIGATION / HEADER -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        
        <!-- Action Navigation Buttons -->
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 transition-all border border-slate-200/80">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>

            <a href="{{ route('admin.ipf.accounts.index') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition-all border border-brand-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Accounts
            </a>
        </div>

        <!-- Header Branding or Title -->
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">IPF Analytics</span>
        </div>
    </div>
</header>

<!-- MAIN CONTAINER -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- PAGE TITLE -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">IPF Portfolio Report</h1>
        <p class="text-sm text-slate-500 mt-1">High-level financial breakdown and performance metrics across financing accounts.</p>
    </div>

    <!-- PORTFOLIO SUMMARY STAT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        
        <!-- Total Accounts -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Accounts</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ $summary['total_accounts'] }}</span>
                <span class="p-2 rounded-xl bg-slate-100 text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
        </div>

        <!-- Active Accounts -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Accounts</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-brand-600">{{ $summary['active_accounts'] }}</span>
                <span class="p-2 rounded-xl bg-brand-50 text-brand-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
            </div>
        </div>

        <!-- Completed Accounts -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Completed</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-emerald-600">{{ $summary['completed_accounts'] }}</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
        </div>

        <!-- Defaulted Accounts -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Defaulted</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-rose-600">{{ $summary['defaulted_accounts'] }}</span>
                <span class="p-2 rounded-xl bg-rose-50 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
            </div>
        </div>

        <!-- Total Financed Amount -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Financed</span>
            <div class="mt-3">
                <div class="text-2xl font-bold text-slate-900">{{ number_format($summary['total_financed'], 2) }}</div>
                <div class="text-xs text-slate-400 mt-1">Cumulative principal issued</div>
            </div>
        </div>

        <!-- Total Collected -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Collected</span>
            <div class="mt-3">
                <div class="text-2xl font-bold text-emerald-600">{{ number_format($summary['total_collected'], 2) }}</div>
                <div class="text-xs text-emerald-700/70 font-medium mt-1">Successful repayments</div>
            </div>
        </div>

        <!-- Total Outstanding -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Outstanding</span>
            <div class="mt-3">
                <div class="text-2xl font-bold text-slate-900">{{ number_format($summary['total_outstanding'], 2) }}</div>
                <div class="text-xs text-slate-400 mt-1">Remaining principal due</div>
            </div>
        </div>

        <!-- Overdue Installments -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Overdue Status</span>
            <div class="mt-3">
                <div class="text-2xl font-bold text-rose-600">
                    {{ $summary['overdue_installments'] }} <span class="text-xs font-normal text-slate-400">installment(s)</span>
                </div>
                <div class="text-xs font-semibold text-rose-600/80 mt-1">
                    {{ number_format($summary['overdue_amount'], 2) }} outstanding
                </div>
            </div>
        </div>

    </div>

    <!-- BREAKDOWN BY PLAN TABLE -->
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/80">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800">Breakdown by Financing Plan</h2>
            <span class="text-xs font-medium text-slate-400">Per-plan aggregation</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/70 text-slate-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 text-left font-bold">Plan Name</th>
                        <th class="px-6 py-3.5 text-right font-bold">Total Accounts</th>
                        <th class="px-6 py-3.5 text-right font-bold">Financed</th>
                        <th class="px-6 py-3.5 text-right font-bold">Collected</th>
                        <th class="px-6 py-3.5 text-right font-bold">Outstanding</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($byPlan as $row)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-900">
                                {{ $row->plan->name ?? 'Unknown Plan' }}
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-slate-700">
                                {{ $row->accounts_count }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-800">
                                {{ number_format($row->total_financed, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-emerald-600">
                                {{ number_format($row->total_collected, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-rose-600">
                                {{ number_format($row->total_outstanding, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="font-medium text-slate-600">No IPF activity recorded</p>
                                    <p class="text-xs text-slate-400 mt-1">Plan metrics will automatically appear here as accounts are created.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</main>

</body>
</html>
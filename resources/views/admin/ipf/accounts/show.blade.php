<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPF Account Detail - Admin Portal</title>
    
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

@php
    $installmentColors = [
        'pending' => 'bg-slate-100 text-slate-600 border-slate-200',
        'partial' => 'bg-amber-50 text-amber-700 border-amber-200',
        'paid'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'overdue' => 'bg-rose-50 text-rose-700 border-rose-200',
    ];
@endphp

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
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Account Details</span>
        </div>
    </div>
</header>

<!-- MAIN CONTAINER -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- PAGE HEADER & ACTION BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                {{ $account->user->name ?? 'Customer' }}
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Plan: <span class="font-semibold text-slate-700">{{ $account->plan->name ?? 'IPF Plan' }}</span>
            </p>
        </div>

        <form action="{{ route('admin.ipf.accounts.mark-overdue', $account->id) }}" method="POST"
              onsubmit="return confirm('Mark all past-due pending installments on this account as overdue?');">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200/80 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 shadow-sm transition-all">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Mark Overdue Installments
            </button>
        </form>
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

    <!-- SUMMARY METRIC CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        
        <!-- Total Premium -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Premium</span>
            <div class="mt-2 text-xl font-bold text-slate-900">{{ number_format($account->total_premium, 2) }}</div>
        </div>

        <!-- Down Payment -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Down Payment</span>
            <div class="mt-2">
                <div class="text-xl font-bold text-slate-900">{{ number_format($account->down_payment_amount, 2) }}</div>
                <div class="text-xs text-slate-400 font-medium mt-0.5">{{ number_format($account->down_payment_percent, 2) }}% of total</div>
            </div>
        </div>

        <!-- Financed Amount -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Financed</span>
            <div class="mt-2 text-xl font-bold text-slate-900">{{ number_format($account->financed_amount, 2) }}</div>
        </div>

        <!-- Total Paid -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Paid</span>
            <div class="mt-2 text-xl font-bold text-emerald-600">{{ number_format($account->total_paid, 2) }}</div>
        </div>

        <!-- Remaining Amount -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Remaining</span>
            <div class="mt-2 text-xl font-bold text-rose-600">{{ number_format($account->remaining_amount, 2) }}</div>
        </div>

    </div>

    <!-- REPAYMENT PROGRESS CARD -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 mb-8 shadow-sm">
        <div class="flex justify-between items-center text-sm font-semibold mb-2">
            <span class="text-slate-700">Repayment Completion</span>
            <span class="text-brand-600 font-bold">{{ number_format($account->progress_percent, 1) }}%</span>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden border border-slate-100">
            <div class="bg-brand-500 h-3 rounded-full transition-all duration-300" style="width: {{ min(100, $account->progress_percent) }}%"></div>
        </div>
        <div class="flex flex-wrap items-center justify-between text-xs text-slate-500 mt-3 pt-3 border-t border-slate-100 gap-2">
            <div>
                <span class="font-semibold text-slate-700">Schedule:</span> 
                {{ $account->start_date?->format('d M Y') }} &rarr; {{ $account->expected_end_date?->format('d M Y') }}
            </div>
            <div class="flex items-center gap-3 text-slate-600">
                <span><strong>Order Ref:</strong> {{ $account->order->reference_no ?? '—' }}</span>
                <span>&bull;</span>
                <span><strong>Vehicle Reg:</strong> {{ $account->order->registration_number ?? '—' }}</span>
            </div>
        </div>
    </div>

    <!-- TABLES GRID (INSTALLMENTS & PAYMENTS) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- DAILY BREAKDOWN (INSTALLMENTS) -->
        <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/80 flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Daily Breakdown</h3>
                <span class="text-xs text-slate-400 font-medium">Scheduled installments</span>
            </div>
            <div class="max-h-[520px] overflow-y-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 sticky top-0 z-10 text-slate-400 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold">Day</th>
                            <th class="px-4 py-3 text-left font-bold">Due Date</th>
                            <th class="px-4 py-3 text-right font-bold">Due</th>
                            <th class="px-4 py-3 text-right font-bold">Paid</th>
                            <th class="px-4 py-3 text-left font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($account->installments as $installment)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-3 font-semibold text-slate-800">#{{ $installment->installment_number }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $installment->due_date->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-slate-800">{{ number_format($installment->amount_due, 2) }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-600">{{ number_format($installment->amount_paid, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $installmentColors[$installment->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                        {{ ucfirst($installment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">No installments generated.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAYMENT HISTORY -->
        <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/80 flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Payment History</h3>
                <span class="text-xs text-slate-400 font-medium">Recorded transactions</span>
            </div>
            <div class="max-h-[520px] overflow-y-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 sticky top-0 z-10 text-slate-400 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold">Date</th>
                            <th class="px-4 py-3 text-right font-bold">Amount</th>
                            <th class="px-4 py-3 text-left font-bold">Method</th>
                            <th class="px-4 py-3 text-left font-bold">Reference</th>
                            <th class="px-4 py-3 text-left font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($account->payments as $payment)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $payment->paid_at?->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-600 whitespace-nowrap">{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-4 py-3 text-slate-700 font-medium">{{ $payment->payment_method ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-400 text-xs font-mono">{{ $payment->transaction_reference }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">No payments recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</main>

</body>
</html>
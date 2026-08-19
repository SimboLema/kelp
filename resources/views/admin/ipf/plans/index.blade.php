<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPF Plans - Admin Portal</title>

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
        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 shadow-sm shadow-orange-500/30 transition-all border border-brand-500">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">IPF Configuration</span>
        </div>
    </div>
</header>

<!-- MAIN CONTAINER -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- PAGE TITLE & MAIN ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">IPF Plans</h1>
            <p class="text-sm text-slate-500 mt-1">Manage financing rates, terms, and daily calculation rules for customer accounts.</p>
        </div>

        <a href="{{ route('admin.ipf.plans.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Plan
        </a>
    </div>

    <!-- FLASH MESSAGES -->
    @if (session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- PLANS TABLE -->
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-slate-200/80">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50/80 text-slate-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 text-left font-bold">Plan Name</th>
                        <th class="px-6 py-3.5 text-left font-bold">Duration</th>
                        <th class="px-6 py-3.5 text-left font-bold">Down Payment</th>
                        <th class="px-6 py-3.5 text-left font-bold">Method</th>
                        <th class="px-6 py-3.5 text-left font-bold">Daily Rate</th>
                        <th class="px-6 py-3.5 text-center font-bold">Accounts</th>
                        <th class="px-6 py-3.5 text-left font-bold">Status</th>
                        <th class="px-6 py-3.5 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($plans as $plan)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $plan->name }}</div>
                                @if ($plan->description)
                                    <div class="text-slate-400 text-xs mt-0.5">{{ \Illuminate\Support\Str::limit($plan->description, 60) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700 whitespace-nowrap">
                                {{ $plan->duration_days }} day(s)
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800 whitespace-nowrap">
                                {{ number_format($plan->down_payment_percent, 2) }}%
                            </td>
                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                @if ($plan->calculation_method === 'fixed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">Fixed daily split</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-brand-50 text-brand-700">Remaining balance %</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700 whitespace-nowrap">
                                {{ $plan->daily_rate_percent !== null ? number_format($plan->daily_rate_percent, 2) . '%' : '—' }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-800">
                                {{ $plan->accounts_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($plan->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.ipf.plans.edit', $plan) }}"
                                       class="text-brand-600 hover:text-brand-700 hover:underline">Edit</a>

                                    <form action="{{ route('admin.ipf.plans.destroy', $plan) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Delete this plan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <p class="font-medium text-slate-600">No IPF plans found</p>
                                    <p class="text-xs text-slate-400 mt-1">Get started by creating a new financing plan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION LINK CONTAINER -->
    <div class="mt-6">
        {{ $plans->links() }}
    </div>

</main>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New IPF Plan - Admin Portal</title>
    
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

            <a href="{{ route('admin.ipf.plans.index') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition-all border border-brand-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Plans
            </a>
        </div>

        <!-- Header Branding or Title -->
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Plan Management</span>
        </div>
    </div>
</header>

<!-- MAIN CONTAINER -->
<main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- PAGE HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">New IPF Plan</h1>
        <p class="text-sm text-slate-500 mt-1">Configure parameters and daily rates for customer financing choices.</p>
    </div>

    <!-- FORM CARD -->
    <form action="{{ route('admin.ipf.plans.store') }}" method="POST"
          class="bg-white shadow-sm rounded-2xl border border-slate-200/80 p-6 sm:p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Plan Name -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Plan Name</label>
                <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" placeholder="e.g. 30-Day Flexi Plan"
                       class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-sm focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all outline-none">
                @error('name') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Description</label>
                <textarea name="description" rows="3" placeholder="Brief explanation of terms for internal or customer reference..."
                          class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-sm focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all outline-none">{{ old('description', $plan->description ?? '') }}</textarea>
                @error('description') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Duration (days) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Duration (Days)</label>
                <input type="number" min="1" name="duration_days" value="{{ old('duration_days', $plan->duration_days ?? '') }}" placeholder="30"
                       class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-sm focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all outline-none">
                @error('duration_days') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Down Payment (%) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Down Payment (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="down_payment_percent"
                       value="{{ old('down_payment_percent', $plan->down_payment_percent ?? '') }}" placeholder="20.00"
                       class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-sm focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all outline-none">
                @error('down_payment_percent') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Calculation Method -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Calculation Method</label>
                <select name="calculation_method" id="calculation_method"
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-sm focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all outline-none">
                    @php $method = old('calculation_method', $plan->calculation_method ?? 'fixed'); @endphp
                    <option value="fixed" @selected($method === 'fixed')>Fixed daily split (financed ÷ days)</option>
                    <option value="remaining_balance_percentage" @selected($method === 'remaining_balance_percentage')>
                        Percentage of remaining balance, daily
                    </option>
                </select>
                @error('calculation_method') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Daily Rate (%) -->
            <div id="daily_rate_wrapper">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Daily Rate (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="daily_rate_percent"
                       value="{{ old('daily_rate_percent', $plan->daily_rate_percent ?? '') }}" placeholder="1.50"
                       class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-sm focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all outline-none">
                <p class="mt-1.5 text-xs text-slate-400">Applies only to "percentage of remaining balance".</p>
                @error('daily_rate_percent') <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Active Checkbox -->
            <div class="md:col-span-2 pt-2">
                <label for="is_active" class="inline-flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           @checked(old('is_active', $plan->is_active ?? true))
                           class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-sm font-semibold text-slate-700">Active (Visible to customers selecting financing)</span>
                </label>
            </div>

        </div>

        <!-- FORM SUBMIT BUTTONS -->
        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.ipf.plans.index') }}" 
               class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 transition-all">
                Cancel
            </a>
            <button type="submit"
                    class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all">
                Create Plan
            </button>
        </div>
    </form>
</main>

<script>
    // Toggle the daily rate input based on calculation method selection
    (function () {
        var methodSelect = document.getElementById('calculation_method');
        var wrapper = document.getElementById('daily_rate_wrapper');

        function toggle() {
            wrapper.style.display = methodSelect.value === 'remaining_balance_percentage' ? '' : 'none';
        }

        methodSelect.addEventListener('change', toggle);
        toggle();
    })();
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Agents - Admin Portal</title>
    
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
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        
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

        <!-- Header Branding / Status Indicator -->
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Agent Onboarding</span>
        </div>
    </div>
</header>

<!-- MAIN CONTENT WRAPPER -->
<main class="p-6 max-w-[1600px] mx-auto space-y-6">

    <!-- Top Header & Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Agents Management</h1>
            <p class="text-sm text-slate-500 mt-0.5">Overview of active platform agents and onboarding form.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white text-xs font-semibold text-slate-700 border border-slate-200/80 shadow-sm">
                Total Agents: <span class="ml-1.5 font-bold text-brand-600 text-sm">{{ $agents->total() }}</span>
            </span>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if (session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- MAIN CONTAINER: Two Independent Columns -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        <!-- LEFT COLUMN: Agents Table List (8 cols) -->
        <div class="xl:col-span-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden h-fit">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800">Agent Directory</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-200/80 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-3.5 px-6">#</th>
                                <th class="py-3.5 px-6">Name</th>
                                <th class="py-3.5 px-6">Phone Number</th>
                                <th class="py-3.5 px-6">Created Date</th>
                                <th class="py-3.5 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($agents as $agent)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-4 px-6 font-medium text-slate-400">
                                        {{ $loop->iteration + ($agents->currentPage() - 1) * $agents->perPage() }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-brand-50 text-brand-600 font-bold flex items-center justify-center text-xs border border-brand-100 shrink-0">
                                                {{ strtoupper(substr($agent->name, 0, 1)) }}
                                            </div>
                                            <span class="font-bold text-slate-800">{{ $agent->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-slate-700 whitespace-nowrap">
                                        {{ $agent->phone_number }}
                                    </td>
                                    <td class="py-4 px-6 text-slate-500 whitespace-nowrap">
                                        {{ $agent->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="#" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200 transition-colors text-xs">
                                                Edit
                                            </a>

                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Delete this agent?')"
                                                        class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 font-semibold hover:bg-rose-100 transition-colors text-xs">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-400">
                                        <div class="max-w-xs mx-auto">
                                            <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <p class="text-sm font-semibold text-slate-600">No agents found</p>
                                            <p class="text-xs text-slate-400 mt-1">Add your first agent using the onboard form.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($agents->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $agents->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT COLUMN: Create Agent Form (4 cols) -->
        <div class="xl:col-span-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-7 h-fit">
                <div class="mb-5 pb-3 border-b border-slate-100">
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">Create Agent</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Add a new insurance or platform agent.</p>
                </div>

                <form method="POST" action="{{ route('admin.agents.create') }}" class="space-y-5">
                    @csrf

                    <!-- Full Name -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                            Full Name
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="e.g. John Doe"
                            class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all outline-none"
                        >
                        @error('name')
                            <span class="text-xs font-semibold text-rose-600 block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                            Phone Number
                        </label>
                        <input
                            type="tel"
                            name="phone_number"
                            value="{{ old('phone_number') }}"
                            required
                            placeholder="e.g. +255 700 000 000"
                            class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all outline-none"
                        >
                        @error('phone_number')
                            <span class="text-xs font-semibold text-rose-600 block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                            Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full rounded-xl border-slate-200 bg-slate-50/50 p-3 text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all outline-none"
                        >
                        @error('password')
                            <span class="text-xs font-semibold text-rose-600 block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full py-3 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm hover:shadow transition-all flex items-center justify-center cursor-pointer"
                        >
                            <span>Create Agent</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>

</body>
</html>
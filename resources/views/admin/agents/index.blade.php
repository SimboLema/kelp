@extends('layouts.admin')

@section('title', 'Manage Agents')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto space-y-6 font-sans">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Agents Management</h1>
            <p class="text-xs text-slate-500 mt-0.5">Overview of active platform agents and onboarding form.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-slate-100 text-xs font-semibold text-slate-700 border border-slate-200">
                Total Agents: <span class="ml-1 font-bold text-orange-600">{{ $agents->total() }}</span>
            </span>
        </div>
    </div>

    <!-- MAIN CONTAINER: Two Independent Columns -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        <!-- LEFT COLUMN: Agents Table List (8 cols) - INDEPENDENT DIV -->
        <div class="xl:col-span-8">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden h-fit">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800">Agent Directory</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-200/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-5">#</th>
                                <th class="py-3 px-5">Name</th>
                                <th class="py-3 px-5">Phone Number</th>
                                <th class="py-3 px-5">Created Date</th>
                                <th class="py-3 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($agents as $agent)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-5 font-medium text-slate-400">
                                        {{ $loop->iteration + ($agents->currentPage() - 1) * $agents->perPage() }}
                                    </td>
                                    <td class="py-3.5 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-xs border border-slate-200">
                                                {{ strtoupper(substr($agent->name, 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-slate-800">{{ $agent->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-5 font-medium text-slate-600">
                                        {{ $agent->phone_number }}
                                    </td>
                                    <td class="py-3.5 px-5 text-slate-500 whitespace-nowrap">
                                        {{ $agent->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="#" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 font-medium hover:bg-slate-200 transition-colors text-xs">
                                                Edit
                                            </a>

                                            <form action="#" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Delete this agent?')"
                                                        class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 font-medium hover:bg-rose-100 transition-colors text-xs">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-400">
                                        <p class="text-sm font-medium">No agents found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($agents->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50">
                        {{ $agents->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT COLUMN: Create Agent Form (4 cols) - INDEPENDENT DIV -->
        <div class="xl:col-span-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 h-fit">
                <div class="mb-5 pb-3 border-b border-slate-100">
                    <h2 class="text-base font-bold text-slate-900 tracking-tight">Create Agent</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Add a new insurance or platform agent.</p>
                </div>

                <form method="POST" action="{{ route('admin.agents.create') }}" class="space-y-4">
                    @csrf

                    <!-- Full Name -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Full Name
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="e.g. John Doe"
                            class="w-full h-10 px-3.5 rounded-lg bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                        >
                        @error('name')
                            <span class="text-xs text-rose-500 font-medium block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Phone Number
                        </label>
                        <input
                            type="tel"
                            name="phone_number"
                            value="{{ old('phone_number') }}"
                            required
                            placeholder="e.g. +255 700 000 000"
                            class="w-full h-10 px-3.5 rounded-lg bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                        >
                        @error('phone_number')
                            <span class="text-xs text-rose-500 font-medium block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full h-10 px-3.5 rounded-lg bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                        >
                        @error('password')
                            <span class="text-xs text-rose-500 font-medium block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full h-11 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-bold text-xs uppercase tracking-wider rounded-lg shadow-md shadow-orange-500/20 transition-all flex items-center justify-center cursor-pointer"
                        >
                            <span>Create Agent</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

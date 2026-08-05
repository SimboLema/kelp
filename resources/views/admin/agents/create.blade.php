@extends('layouts.admin')

@section('title', 'Manage Agents')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Form Column -->
    <div class="lg:w-1/2">
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl p-8">
            <div class="mb-6">
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Create Agent</h3>
                <p class="text-xs text-slate-400 font-medium">Add a new insurance or platform agent.</p>
            </div>

            <form method="POST" action="{{ route('admin.agents.create') }}" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">
                        Full Name
                    </label>
                    <input type="text" name="name" required placeholder="e.g. John Doe"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-medium">
                    @error('name')
                        <span class="text-xs text-rose-500 font-semibold ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">
                        Phone Number
                    </label>
                    <input type="tel" name="phone_number" required placeholder="e.g. +255 700 000 000"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-medium">
                    @error('phone_number')
                        <span class="text-xs text-rose-500 font-semibold ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">
                        Password
                    </label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-medium">
                    @error('password')
                        <span class="text-xs text-rose-500 font-semibold ml-1">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-4 rounded-2xl shadow-lg shadow-orange-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest text-xs mt-4">
                    <span>Create Agent</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

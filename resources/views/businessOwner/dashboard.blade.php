@extends('layouts.businessOwner')

@section('title', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="stat-card bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Reviews</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalReviews }}</h3>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-inner">
            <i data-lucide="star" class="w-7 h-7"></i>
        </div>
    </div>
</div>
@endsection

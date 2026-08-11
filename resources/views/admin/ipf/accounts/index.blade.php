{{-- @extends('layouts.admin') --}}

@section('title', 'IPF Accounts')

@php
    $statusColors = [
        'pending'   => 'bg-gray-100 text-gray-600',
        'active'    => 'bg-blue-100 text-blue-800',
        'completed' => 'bg-green-100 text-green-800',
        'defaulted' => 'bg-red-100 text-red-800',
        'cancelled' => 'bg-gray-100 text-gray-500',
    ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">IPF Accounts</h1>
        <a href="{{ route('admin.ipf.report') }}" class="text-sm text-indigo-600 hover:underline">View Report →</a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="bg-white shadow-sm rounded-lg border border-gray-200 p-4 mb-4 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer, phone, order ref, reg no."
                   class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 w-64">
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status" class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All</option>
                @foreach (['pending', 'active', 'completed', 'defaulted', 'cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Plan</label>
            <select name="ipf_plan_id" class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All</option>
                @foreach ($plans as $plan)
                    <option value="{{ $plan->id }}" @selected((string) request('ipf_plan_id') === (string) $plan->id)>
                        {{ $plan->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
            Filter
        </button>

        @if (request()->hasAny(['search', 'status', 'ipf_plan_id']))
            <a href="{{ route('admin.ipf.accounts.index') }}" class="text-sm text-gray-500 hover:underline">Clear</a>
        @endif
    </form>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Financed</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider w-40">Progress</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($accounts as $account)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $account->user->name ?? '—' }}</div>
                            <div class="text-gray-500 text-xs">{{ $account->user->phone ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            <div>{{ $account->order->reference_no ?? '—' }}</div>
                            <div class="text-gray-500 text-xs">{{ $account->order->registration_number ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $account->plan->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ number_format($account->financed_amount, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ number_format($account->total_paid, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ number_format($account->remaining_amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min(100, $account->progress_percent) }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ number_format($account->progress_percent, 1) }}%</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$account->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($account->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.ipf.accounts.show', $account->id) }}" class="text-indigo-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">No IPF accounts match these filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $accounts->links() }}
    </div>
</div>
{{-- @endsection --}}

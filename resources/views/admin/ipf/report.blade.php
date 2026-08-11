@extends('layouts.admin')

@section('title', 'IPF Report')

@section('content')
<div class="max-w-6xl mx-auto py-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('admin.ipf.accounts.index') }}" class="text-sm text-gray-500 hover:underline">← Back to accounts</a>
            <h1 class="text-2xl font-semibold text-gray-800 mt-1">IPF Report</h1>
        </div>
    </div>

    {{-- Portfolio summary --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Total Accounts</div>
            <div class="text-xl font-semibold text-gray-900">{{ $summary['total_accounts'] }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Active</div>
            <div class="text-xl font-semibold text-blue-700">{{ $summary['active_accounts'] }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Completed</div>
            <div class="text-xl font-semibold text-green-700">{{ $summary['completed_accounts'] }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Defaulted</div>
            <div class="text-xl font-semibold text-red-700">{{ $summary['defaulted_accounts'] }}</div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Total Financed</div>
            <div class="text-xl font-semibold text-gray-900">{{ number_format($summary['total_financed'], 2) }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Total Collected</div>
            <div class="text-xl font-semibold text-green-700">{{ number_format($summary['total_collected'], 2) }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Total Outstanding</div>
            <div class="text-xl font-semibold text-red-700">{{ number_format($summary['total_outstanding'], 2) }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Overdue</div>
            <div class="text-xl font-semibold text-red-700">
                {{ $summary['overdue_installments'] }} <span class="text-sm font-normal text-gray-500">installment(s)</span>
            </div>
            <div class="text-xs text-gray-500">{{ number_format($summary['overdue_amount'], 2) }} outstanding</div>
        </div>
    </div>

    {{-- By plan --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-100 font-medium text-gray-800">Breakdown by Plan</div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Accounts</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Financed</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Collected</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Outstanding</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($byPlan as $row)
                    <tr>
                        <td class="px-4 py-3 text-gray-900">{{ $row->plan->name ?? 'Unknown Plan' }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ $row->accounts_count }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ number_format($row->total_financed, 2) }}</td>
                        <td class="px-4 py-3 text-right text-green-700">{{ number_format($row->total_collected, 2) }}</td>
                        <td class="px-4 py-3 text-right text-red-700">{{ number_format($row->total_outstanding, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No IPF activity yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
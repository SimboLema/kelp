@extends('layouts.admin')

@section('title', 'IPF Account Detail')

@php
    $installmentColors = [
        'pending' => 'bg-gray-100 text-gray-600',
        'partial' => 'bg-amber-100 text-amber-800',
        'paid'    => 'bg-green-100 text-green-800',
        'overdue' => 'bg-red-100 text-red-800',
    ];
@endphp

@section('content')
<div class="max-w-6xl mx-auto py-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('admin.ipf.accounts.index') }}" class="text-sm text-gray-500 hover:underline">← Back to accounts</a>
            <h1 class="text-2xl font-semibold text-gray-800 mt-1">
                {{ $account->user->name ?? 'Customer' }} — {{ $account->plan->name ?? 'IPF Plan' }}
            </h1>
        </div>

        <form action="{{ route('admin.ipf.accounts.mark-overdue', $account->id) }}" method="POST"
              onsubmit="return confirm('Mark all past-due pending installments on this account as overdue?');">
            @csrf
            <button type="submit" class="px-3 py-2 bg-white border border-gray-300 text-sm rounded-md hover:bg-gray-50">
                Mark Overdue Installments
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Summary cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Total Premium</div>
            <div class="text-lg font-semibold text-gray-900">{{ number_format($account->total_premium, 2) }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Down Payment</div>
            <div class="text-lg font-semibold text-gray-900">{{ number_format($account->down_payment_amount, 2) }}</div>
            <div class="text-xs text-gray-400">{{ number_format($account->down_payment_percent, 2) }}%</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Financed</div>
            <div class="text-lg font-semibold text-gray-900">{{ number_format($account->financed_amount, 2) }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Paid</div>
            <div class="text-lg font-semibold text-green-700">{{ number_format($account->total_paid, 2) }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="text-xs text-gray-500 uppercase">Remaining</div>
            <div class="text-lg font-semibold text-red-700">{{ number_format($account->remaining_amount, 2) }}</div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
        <div class="flex justify-between text-sm text-gray-600 mb-1">
            <span>Progress</span>
            <span>{{ number_format($account->progress_percent, 1) }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ min(100, $account->progress_percent) }}%"></div>
        </div>
        <div class="text-xs text-gray-500 mt-2">
            {{ $account->start_date?->format('d M Y') }} → {{ $account->expected_end_date?->format('d M Y') }}
            &middot; Order {{ $account->order->reference_no ?? '—' }}
            &middot; Vehicle {{ $account->order->registration_number ?? '—' }}
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Installment breakdown --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-100 font-medium text-gray-800">Daily Breakdown</div>
            <div class="max-h-[520px] overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase text-xs">Day</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase text-xs">Due Date</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-500 uppercase text-xs">Due</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-500 uppercase text-xs">Paid</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase text-xs">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($account->installments as $installment)
                            <tr>
                                <td class="px-3 py-2 text-gray-700">{{ $installment->installment_number }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $installment->due_date->format('d M Y') }}</td>
                                <td class="px-3 py-2 text-right text-gray-700">{{ number_format($installment->amount_due, 2) }}</td>
                                <td class="px-3 py-2 text-right text-gray-700">{{ number_format($installment->amount_paid, 2) }}</td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $installmentColors[$installment->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($installment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-gray-500">No installments generated.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Payment history --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-100 font-medium text-gray-800">Payment History</div>
            <div class="max-h-[520px] overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase text-xs">Date</th>
                            <th class="px-3 py-2 text-right font-medium text-gray-500 uppercase text-xs">Amount</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase text-xs">Method</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase text-xs">Reference</th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase text-xs">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($account->payments as $payment)
                            <tr>
                                <td class="px-3 py-2 text-gray-700">{{ $payment->paid_at?->format('d M Y H:i') }}</td>
                                <td class="px-3 py-2 text-right text-gray-700">{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ $payment->payment_method ?? '—' }}</td>
                                <td class="px-3 py-2 text-gray-500 text-xs">{{ $payment->transaction_reference }}</td>
                                <td class="px-3 py-2 text-gray-700">{{ ucfirst($payment->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-gray-500">No payments recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
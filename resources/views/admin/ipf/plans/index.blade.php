{{-- @extends('layouts.admin') --}}

@section('title', 'IPF Plans')

@section('content')
<div class="max-w-6xl mx-auto py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">IPF Plans</h1>
        <a href="{{ route('admin.ipf.plans.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
            + New Plan
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Down Payment</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Method</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Daily Rate</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Accounts</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($plans as $plan)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $plan->name }}</div>
                            @if ($plan->description)
                                <div class="text-gray-500 text-xs">{{ \Illuminate\Support\Str::limit($plan->description, 60) }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $plan->duration_days }} day(s)</td>
                        <td class="px-4 py-3 text-gray-700">{{ number_format($plan->down_payment_percent, 2) }}%</td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $plan->calculation_method === 'fixed' ? 'Fixed daily split' : 'Remaining balance %' }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $plan->daily_rate_percent !== null ? number_format($plan->daily_rate_percent, 2) . '%' : '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $plan->accounts_count }}</td>
                        <td class="px-4 py-3">
                            @if ($plan->is_active)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.ipf.plans.edit', $plan) }}" class="text-indigo-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.ipf.plans.destroy', $plan) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">No IPF plans yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $plans->links() }}
    </div>
</div>
{{-- @endsection --}}
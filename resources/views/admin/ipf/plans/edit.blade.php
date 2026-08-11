@extends('layouts.admin')

@section('title', 'Edit IPF Plan')

@section('content')
<div class="max-w-2xl mx-auto py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Plan — {{ $plan->name }}</h1>
        <a href="{{ route('admin.ipf.plans.index') }}" class="text-sm text-gray-500 hover:underline">← Back to plans</a>
    </div>

    @if ($plan->accounts()->exists())
        <div class="mb-4 rounded-md bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
            This plan already has IPF accounts against it. Changing duration or rates only affects new accounts —
            existing customers keep the schedule they were given when they signed up.
        </div>
    @endif

    <form action="{{ route('admin.ipf.plans.update', $plan) }}" method="POST"
          class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
        @method('PUT')
        @include('admin.ipf.plans._form')

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
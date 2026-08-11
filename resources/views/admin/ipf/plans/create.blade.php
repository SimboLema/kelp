{{-- @extends('layouts.admin') --}}

@section('title', 'New IPF Plan')

@section('content')
<div class="max-w-2xl mx-auto py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">New IPF Plan</h1>
        <a href="{{ route('admin.ipf.plans.index') }}" class="text-sm text-gray-500 hover:underline">← Back to plans</a>
    </div>

    <form action="{{ route('admin.ipf.plans.store') }}" method="POST"
          class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
        @include('admin.ipf.plans._form')

        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                Create Plan
            </button>
        </div>
    </form>
</div>
{{-- @endsection --}}
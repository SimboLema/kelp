@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Table Column -->
    <div class="lg:w-2/3">
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Category Name</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date Created</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody" class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                    <tr id="category-row-{{ $category->id }}" class="hover:bg-slate-50 transition-colors">
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-slate-700">{{ $category->name }}</span>
                        </td>
                        <td class="px-8 py-5 text-xs text-slate-400 font-medium">
                            {{ $category->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button onclick="deleteCategory({{ $category->id }})"
                                    class="text-rose-500 hover:bg-rose-50 p-2 rounded-lg transition-colors"
                                    title="Delete Category">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="3" class="px-8 py-20 text-center text-slate-400 font-medium italic">No categories found. Start by adding one!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Column -->
    <div class="lg:w-1/3">
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl p-8 sticky top-28">
            <div class="mb-6">
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Add New Category</h3>
                <p class="text-xs text-slate-400 font-medium">Create a new classification for vendors.</p>
            </div>

            <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">
                        Category Name
                    </label>

                    <input type="text" name="name" id="name" required placeholder="e.g. Fine Dining"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-medium">
                    <span id="nameError" class="text-xs text-rose-500 font-semibold hidden ml-1"></span>
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-4 rounded-2xl shadow-lg shadow-orange-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest text-xs">
                    <span>Save Category</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('categoryForm');
    const submitBtn = document.getElementById('submitBtn');
    const nameInput = document.getElementById('name');
    const nameError = document.getElementById('nameError');

    if (!form) {
        console.error('Category form not found.');
        return;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Clear previous error
        nameError.textContent = '';
        nameError.classList.add('hidden');

        const name = nameInput.value.trim();

        if (!name) {
            nameError.textContent = 'Category name is required.';
            nameError.classList.remove('hidden');
            return;
        }

        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Saving...';

        try {

            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]'
                    ).getAttribute('content'),

                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to create category.');
            }

            // Success
            alert(data.message || 'Category created successfully.');

            // Reset form
            form.reset();

            // Reload page so the new category appears
            window.location.reload();

        } catch (error) {

            console.error('Category error:', error);

            nameError.textContent = error.message;
            nameError.classList.remove('hidden');

        } finally {

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Save Category</span>';

        }
    });

});
</script>
@endpush

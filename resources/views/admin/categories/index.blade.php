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
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('nameError');
        const submitBtn = document.getElementById('submitBtn');
        const bodyComponent = document.querySelector('body').__x.$data;

        nameError.classList.add('hidden');
        nameError.innerText = '';

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

        axios.post("{{ route('admin.categories.store') }}", {
            name: nameInput.value
        })
        .then(function(response) {
            bodyComponent.showNotification(response.data.message || 'Category saved successfully!');
            nameInput.value = '';

            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow) emptyRow.remove();

            const newCategory = response.data.category;
            const tableBody = document.getElementById('categoryTableBody');

            const newRowHtml = `
                <tr id="category-row-${newCategory.id}" class="hover:bg-slate-50 transition-colors opacity-0 duration-300">
                    <td class="px-8 py-5">
                        <span class="text-sm font-bold text-slate-700">${newCategory.name}</span>
                    </td>
                    <td class="px-8 py-5 text-xs text-slate-400 font-medium">
                        ${response.data.created_at || 'Just now'}
                    </td>
                    <td class="px-8 py-5 text-right">
                        <button onclick="deleteCategory(${newCategory.id})" class="text-rose-500 hover:bg-rose-50 p-2 rounded-lg transition-colors" title="Delete Category">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </button>
                    </td>
                </tr>
            `;

            tableBody.insertAdjacentHTML('afterbegin', newRowHtml);

            setTimeout(() => {
                const insertedRow = document.getElementById(`category-row-${newCategory.id}`);
                if(insertedRow) insertedRow.classList.remove('opacity-0');
            }, 50);
        })
        .catch(function(error) {
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                if (errors.name) {
                    nameError.innerText = errors.name[0];
                    nameError.classList.remove('hidden');
                }
            } else {
                bodyComponent.showNotification('Failed to create category.', 'error');
            }
        })
        .finally(function() {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        });
    });

    function deleteCategory(categoryId) {
        if (!confirm('Are you sure you want to delete this category?')) return;

        const bodyComponent = document.querySelector('body').__x.$data;

        axios.delete(`/admin/categories/${categoryId}`)
        .then(function(response) {
            bodyComponent.showNotification('Category deleted successfully.');
            const row = document.getElementById(`category-row-${categoryId}`);
            if (row) {
                row.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => row.remove(), 300);
            }
        })
        .catch(function(error) {
            bodyComponent.showNotification('Could not delete category.', 'error');
        });
    }
</script>
@endpush

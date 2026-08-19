<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Categories | KELP</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316', // Orange Accent
                            600: '#ea580c',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen">

<!-- TOP NAVIGATION / HEADER -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-30">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        <!-- Header Action Navigation -->
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 shadow-sm shadow-orange-500/30 transition-all border border-brand-500">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
            <div class="h-5 w-px bg-slate-200 hidden sm:block"></div>
            <div>
                <h1 class="text-sm font-bold text-slate-800">Category Management</h1>
                <p class="text-[11px] text-slate-400 font-medium hidden sm:block">Classify and organize business vendors.</p>
            </div>
        </div>

        <!-- Header Branding / Status Indicator -->
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">System Settings</span>
        </div>
    </div>
</header>

<!-- MAIN CONTENT CONTAINER -->
<main class="py-10 px-4 sm:px-6 lg:px-8 max-w-[1600px] mx-auto">

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Table Column -->
        <div class="lg:w-2/3">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Active Categories</h2>
                        <p class="text-xs text-slate-400 font-medium">All existing vendor classifications available in system.</p>
                    </div>
                    <span class="text-xs font-bold text-brand-600 bg-brand-50 border border-brand-100 px-3 py-1 rounded-full">
                        {{ count($categories) }} Categories
                    </span>
                </div>

                <div class="overflow-x-auto">
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
                                            class="text-rose-500 hover:bg-rose-50 p-2 rounded-lg transition-colors cursor-pointer"
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
        </div>

        <!-- Form Column -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl p-8 sticky top-24">
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
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all text-sm font-medium">
                        <span id="nameError" class="text-xs text-rose-500 font-semibold hidden ml-1"></span>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full bg-brand-500 hover:bg-brand-600 text-white font-extrabold py-4 rounded-2xl shadow-lg shadow-brand-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest text-xs cursor-pointer">
                        <span>Save Category</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

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

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
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

async function deleteCategory(id) {
    if (!confirm('Are you sure you want to delete this category?')) return;

    try {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

        const response = await fetch(`/admin/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to delete category.');
        }

        alert(data.message || 'Category deleted successfully.');
        const row = document.getElementById(`category-row-${id}`);
        if (row) row.remove();

    } catch (error) {
        console.error('Delete category error:', error);
        alert(error.message);
    }
}
</script>

</body>
</html>

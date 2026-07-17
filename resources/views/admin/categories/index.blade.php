<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KELP | Manage Categories</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .sidebar-item-active { border-left: 4px solid #f97316; background: #fff7ed; color: #ea580c; font-weight: 700; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-[#F1F5F9] text-slate-900" x-data="{ openVendor: false }">

<div class="min-h-screen flex">
    <aside class="hidden md:flex flex-col w-72 bg-white border-r border-slate-200 h-screen sticky top-0 shadow-sm">
        <div class="p-8 mb-4">
            <img src="{{ asset('assets/images/logo.png') }}" width="120" class="hover:opacity-80 transition cursor-pointer">
        </div>

        <nav class="flex-1 px-4 space-y-1">
            

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Dashboard
            </a>



            <div class="relative">
                <button @click="openVendor = !openVendor"
                    class="w-full flex items-center justify-between px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all group">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2z"/><path d="M22 2h-10v10h10V2z"/><path d="M12 12H2v10h10V12z"/><path d="M22 12h-10v10h10V12z"/></svg>
                        <span class="font-medium">Businesses</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-300" :class="openVendor ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div x-show="openVendor" x-cloak x-transition class="mt-1 ml-4 pl-4 border-l-2 border-slate-100 space-y-1">
                    <a href="{{ route('admin.businessOwner.index') }}" class="block px-4 py-2 text-sm text-slate-500 hover:text-orange-600 font-medium transition-colors">List</a>

                </div>
            </div>

            <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-3.5 sidebar-item-active rounded-r-xl transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m16 6 4 14H4L8 6"/><path d="M12 6V2"/><path d="M9 2h6"/></svg>
                Categories
            </a>
        </nav>

        <div class="p-6 border-t border-slate-100">
            <button class="w-full bg-orange-500 text-white font-semibold py-3 rounded-xl hover:bg-orange-600 shadow-lg">Logout</button>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="h-20 glass border-b border-slate-200 px-10 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Category Management</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Organize businesses by industry types</p>
            </div>
        </header>

        <main class="p-10">
            <div class="flex flex-col lg:flex-row gap-8">

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
                            <tbody class="divide-y divide-slate-100">
                                @forelse($categories as $category)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-8 py-5">
                                        <span class="text-sm font-bold text-slate-700">{{ $category->name }}</span>
                                    </td>
                                    <td class="px-8 py-5 text-xs text-slate-400 font-medium">
                                        {{ $category->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <button class="text-rose-500 hover:bg-rose-50 p-2 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-20 text-center text-slate-400 font-medium italic">No categories found. Start by adding one!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

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
                            </div>

                            <button type="submit"
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-4 rounded-2xl shadow-lg shadow-orange-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest text-xs">
                                Save Category
                            </button>

                        </form>
                        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

                        <script>
                        document.getElementById('categoryForm').addEventListener('submit', function(e) {

                            e.preventDefault(); // stop normal form submission

                            let name = document.getElementById('name').value;

                            axios.post("{{ route('admin.categories.store') }}", {
                                name: name
                            })
                            .then(function(response) {

                                alert(response.data.message);

                                // clear input
                                document.getElementById('name').value = '';

                            })
                            .catch(function(error) {

                                console.log(error);

                            });

                        });
                        </script>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

</body>
</html>

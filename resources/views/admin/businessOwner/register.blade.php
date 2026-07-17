<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KELP | Register Business & Owner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .sidebar-item-active { border-left: 4px solid #f97316; background: #fff7ed; color: #ea580c; font-weight: 700; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-[#F1F5F9] text-slate-900" x-data="{ openVendor: true }">

<div class="min-h-screen flex">
    <aside class="hidden md:flex flex-col w-72 bg-white border-r border-slate-200 h-screen sticky top-0 shadow-sm">
        <div class="p-8 mb-4">
            <img src="{{ asset('assets/images/logo.png') }}" width="120" class="hover:opacity-80 transition cursor-pointer">
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Dashboard
            </a>
            <div class="relative">
                <button @click="openVendor = !openVendor" class="w-full flex items-center justify-between px-4 py-3.5 sidebar-item-active rounded-r-xl transition-all group">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2z"/><path d="M22 2h-10v10h10V2z"/><path d="M12 12H2v10h10V12z"/><path d="M22 12h-10v10h10V12z"/></svg>
                        <span class="font-medium">Restaurants</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" :class="openVendor ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-show="openVendor" x-cloak x-transition class="mt-1 ml-4 pl-4 border-l-2 border-slate-100 space-y-1">
                    <a href="{{ route('admin.businessOwner.index') }}" class="block px-4 py-2 text-sm text-slate-500 hover:text-orange-600 font-medium transition-colors">List</a>
                </div>
            </div>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="h-20 glass border-b border-slate-200 px-10 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Onboard Owner</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Creating both a user account and business profile</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-orange-500 uppercase tracking-widest hover:underline">Back to List</a>
        </header>

        <main class="p-10 max-w-5xl">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">
                
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-black text-slate-800 italic uppercase tracking-tighter">Registration Form</h3>
                    <p class="text-xs text-slate-400 font-medium">This will generate a UUID-based owner account and a business listing.</p>
                </div>
        
                <form id="businessForm" class="p-8 space-y-10">
                    @csrf
        
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-bold">01</span>
                            <h4 class="font-bold text-slate-700 uppercase text-xs tracking-widest">Security Credentials</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                                <input type="password" name="password" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-medium" placeholder="Min 6 characters">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-medium" placeholder="Repeat password">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-bold">02</span>
                            <h4 class="font-bold text-slate-700 uppercase text-xs tracking-widest">Basic Information</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Business/Owner Name</label>
                                <input type="text" name="name" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:border-orange-500 text-sm font-medium">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Category</label>
                                <select name="category_id" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:border-orange-500 text-sm font-medium">
                                    <option value="category_id">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Phone</label>
                                <input type="text" name="phone" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Email (Optional)</label>
                                <input type="email" name="email" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Website</label>
                                <input type="text" name="website" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Description</label>
                            <textarea name="description" rows="3" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm"></textarea>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-bold">03</span>
                            <h4 class="font-bold text-slate-700 uppercase text-xs tracking-widest">Location</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Street Address</label>
                            <input type="text" name="address" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="text" name="city" placeholder="City" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm">
                            <input type="text" name="country" placeholder="Country" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Latitude</label>
                                <input type="number" step="any" name="latitude" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Longitude</label>
                                <input type="number" step="any" name="longitude" required class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center text-xs font-bold">04</span>
                            <h4 class="font-bold text-slate-700 uppercase text-xs tracking-widest">Media</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Logo</label>
                                <input type="file" name="logo" class="w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Gallery</label>
                                <input type="file" name="images[]" multiple class="w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="pt-8">
                        <button type="submit" id="submitBtn" class="w-full bg-orange-500 text-white font-black py-6 rounded-2xl shadow-xl shadow-orange-500/30 hover:bg-orange-600 transition-all active:scale-[0.99] uppercase tracking-widest">
                            Create Owner Account & Business
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.getElementById('businessForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerText;
    
    // UI Loading State
    submitBtn.innerText = "PROCESSING...";
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

    let form = document.getElementById('businessForm');
    let formData = new FormData(form);

    axios.post("{{ route('admin.business.register') }}", formData)
    .then(function(response) {
        alert("Success: " + response.data.message);
        form.reset();
        window.location.href = "{{ route('admin.businessOwner.index') }}";
    })
    .catch(function(error) {
        submitBtn.innerText = originalText;
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');

        if (error.response && error.response.status === 422) {
            // Validation errors from Laravel
            const errors = error.response.data.errors;
            let errorMsg = "Validation failed:\n";
            for (let field in errors) {
                errorMsg += `- ${errors[field][0]}\n`;
            }
            alert(errorMsg);
        } else {
            alert("An unexpected error occurred. Please check the console.");
            console.error(error);
        }
    });
});
</script>
</body>
</html>
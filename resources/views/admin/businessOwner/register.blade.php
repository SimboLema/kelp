@extends('layouts.admin')

@section('title', 'Register Business & Owner | KELP')
@section('header_title', 'Onboard Owner')
@section('header_subtitle', 'Creating both a user account and business profile.')

@section('content')
    <div class="max-w-5xl mx-auto">
        {{-- Card Container --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">

            {{-- Form Header --}}
            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-black text-slate-800 italic uppercase tracking-tighter">Registration Form</h3>
                    <p class="text-xs text-slate-400 font-medium">This will generate a UUID-based owner account and a business listing.</p>
                </div>
                <a href="{{ route('admin.businessOwner.index') }}" class="text-xs font-bold text-orange-500 uppercase tracking-widest hover:underline">
                    Back to List
                </a>
            </div>

            {{-- Form Content --}}
            <form id="businessForm" class="p-8 space-y-10" enctype="multipart/form-data">
                @csrf

                {{-- Step 01: Security --}}
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

                {{-- Step 02: Basic Information --}}
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
                                <option value="">Select Category</option>
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

                {{-- Step 03: Location --}}
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

                {{-- Step 04: Media --}}
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

                {{-- Action Button --}}
                <div class="pt-8">
                    <button type="submit" id="submitBtn" class="w-full bg-orange-500 text-white font-black py-6 rounded-2xl shadow-xl shadow-orange-500/30 hover:bg-orange-600 transition-all active:scale-[0.99] uppercase tracking-widest">
                        Create Owner Account & Business
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
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
@endpush

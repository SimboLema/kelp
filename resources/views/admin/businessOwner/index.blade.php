@extends('layouts.admin')

@section('title', 'Business Approvals | KELP')
@section('header_title', 'Business Approvals')
@section('header_subtitle', 'Manage and verify new business owner registrations.')

@section('content')
    {{-- Top Action Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Business Approvals</h2>
            <p class="text-slate-500 font-medium">Manage and verify new business owner registrations.</p>
        </div>
        <a href="{{ route('admin.businessOwner.register') }}"
           class="inline-flex items-center justify-center gap-2 bg-orange-500 text-white font-black px-8 py-4 rounded-2xl shadow-xl shadow-orange-500/20 hover:bg-orange-600 hover:shadow-orange-500/30 transition-all active:scale-95 group">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1">
                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
            </svg>
            <span>Register new business</span>
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex gap-4 border-b border-slate-200">
        <button class="pb-4 px-2 text-sm font-bold text-orange-600 border-b-2 border-orange-600" data-status="pending" onclick="loadBusinesses('pending')">Pending Requests</button>
        <button class="pb-4 px-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors" data-status="approved" onclick="loadBusinesses('approved')">Approved</button>
        <button class="pb-4 px-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors" data-status="rejected" onclick="loadBusinesses('rejected')">Suspended</button>
    </div>

    {{-- Business Table Card --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Business Owner</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Business Name</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date Applied</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="businessTableBody" class="divide-y divide-slate-100">
                    @forelse($businesses as $business)
                        <tr class="group hover:bg-slate-50/80 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                                        {{ strtoupper(substr($business->owner->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">
                                            {{ $business->owner->name }}
                                        </p>
                                        <p class="text-xs text-slate-400 italic">
                                            {{ $business->owner->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[11px] font-bold rounded-lg uppercase tracking-wider">
                                    {{ $business->name }}
                                </span>
                            </td>

                            <td class="px-8 py-6 text-sm text-slate-500 font-medium">
                                {{ $business->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="approveBusiness('{{ $business->id }}')" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all font-semibold text-xs">
                                        Approve
                                    </button>
                                    <button onclick="rejectBusiness('{{ $business->id }}')" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all font-semibold text-xs">
                                        Reject
                                    </button>
                                    <button onclick="viewBusiness('{{ $business->id }}')" class="p-2 text-slate-400 hover:text-slate-900 rounded-xl transition-all font-semibold text-xs">
                                        View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-slate-400 font-semibold">
                                No pending businesses found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination Controls --}}
        <div class="p-6 bg-slate-50/30 border-t border-slate-100 flex justify-between items-center">
            <p class="text-xs text-slate-400 font-bold tracking-widest uppercase">Showing 1 of 5 Requests</p>
            <div class="flex gap-2">
                <button class="p-2 rounded-lg border border-slate-200 bg-white text-slate-400 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button class="p-2 rounded-lg border border-slate-200 bg-white text-slate-600 hover:border-orange-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Include Axios via CDN or rely on standard Laravel Vite bundle --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    function approveBusiness(id) {
        axios.post(`/api/admin/business/approve/${id}`)
            .then(response => {
                alert(response.data.message);
                location.reload();
            })
            .catch(error => console.error(error));
    }

    function rejectBusiness(id) {
        axios.post(`/api/admin/business/reject/${id}`)
            .then(response => {
                alert(response.data.message);
                location.reload();
            })
            .catch(error => console.error(error));
    }

    function viewBusiness(id) {
        axios.get(`/api/admin/business/view/${id}`)
            .then(response => {
                alert("Business: " + response.data.business.name);
            })
            .catch(error => console.error(error));
    }

    function loadBusinesses(status) {
        // Tab switching active state
        document.querySelectorAll('[data-status]').forEach(tab => {
            tab.classList.remove('text-orange-600', 'border-orange-600', 'border-b-2');
            tab.classList.add('text-slate-400');
        });

        const activeTab = document.querySelector(`[data-status="${status}"]`);
        if(activeTab) {
            activeTab.classList.remove('text-slate-400');
            activeTab.classList.add('text-orange-600', 'border-b-2', 'border-orange-600');
        }

        // Fetch filtered records
        axios.get(`/api/admin/businesses?status=${status}`)
            .then(response => {
                const businesses = response.data.businesses;
                const tbody = document.getElementById('businessTableBody');
                tbody.innerHTML = '';

                if (!businesses || businesses.length === 0) {
                    tbody.innerHTML = `<tr>
                        <td colspan="4" class="text-center py-10 text-slate-400 font-semibold">
                            No ${status} businesses found
                        </td>
                    </tr>`;
                    return;
                }

                businesses.forEach(business => {
                    tbody.innerHTML += `
                        <tr class="group hover:bg-slate-50/80 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                                        ${business.owner.name.slice(0, 2).toUpperCase()}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">${business.owner.name}</p>
                                        <p class="text-xs text-slate-400 italic">${business.owner.email}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[11px] font-bold rounded-lg uppercase tracking-wider">
                                    ${business.name}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-sm text-slate-500 font-medium">
                                ${new Date(business.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    ${status === 'pending' ? `
                                        <button onclick="approveBusiness('${business.id}')" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all font-semibold text-xs">Approve</button>
                                        <button onclick="rejectBusiness('${business.id}')" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all font-semibold text-xs">Reject</button>
                                    ` : ''}
                                    <button onclick="viewBusiness('${business.id}')" class="p-2 text-slate-400 hover:text-slate-900 rounded-xl transition-all font-semibold text-xs">View</button>
                                </div>
                            </td>
                        </tr>`;
                });
            })
            .catch(err => {
                console.error(err);
                alert("Failed to load businesses");
            });
    }
</script>
@endpush

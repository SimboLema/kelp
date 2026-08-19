<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Approvals | KELP</title>

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
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen">

<!-- TOP NAVIGATION / HEADER -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-30">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        <!-- Action Navigation Buttons -->
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}"
        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 shadow-sm shadow-orange-500/30 transition-all border border-brand-500">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>

            <a href="{{ route('admin.ipf.accounts.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition-all border border-brand-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Accounts
            </a>
        </div>

        <!-- Header Branding / Status Indicator -->
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Business Verification</span>
        </div>
    </div>
</header>

<!-- MAIN CONTENT WRAPPER -->
<main class="p-6 max-w-[1600px] mx-auto space-y-6">

    {{-- Top Action Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Business Approvals</h1>
            <p class="text-slate-500 font-medium mt-1">Manage and verify new business owner registrations.</p>
        </div>
        <a href="{{ route('admin.businessOwner.register') }}"
           class="inline-flex items-center justify-center gap-2 bg-brand-500 text-white font-black px-7 py-3.5 rounded-2xl shadow-lg shadow-brand-500/20 hover:bg-brand-600 hover:shadow-brand-500/30 transition-all active:scale-95 group text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1">
                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
            </svg>
            <span>Register new business</span>
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex gap-4 border-b border-slate-200">
        <button class="pb-3 px-2 text-sm font-bold text-brand-600 border-b-2 border-brand-600 cursor-pointer" data-status="pending" onclick="loadBusinesses('pending')">Pending Requests</button>
        <button class="pb-3 px-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" data-status="approved" onclick="loadBusinesses('approved')">Approved</button>
        <button class="pb-3 px-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors cursor-pointer" data-status="rejected" onclick="loadBusinesses('rejected')">Suspended</button>
    </div>

    {{-- Business Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200/80">
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Business Owner</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Business Name</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date Applied</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="businessTableBody" class="divide-y divide-slate-100">
                    @forelse($businesses as $business)
                        <tr class="group hover:bg-slate-50/80 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 font-bold flex items-center justify-center text-xs border border-brand-100 shrink-0">
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

                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[11px] font-bold rounded-lg uppercase tracking-wider">
                                    {{ $business->name }}
                                </span>
                            </td>

                            <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                                {{ $business->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="approveBusiness('{{ $business->id }}')" class="px-3 py-1.5 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all font-semibold text-xs cursor-pointer">
                                        Approve
                                    </button>
                                    <button onclick="rejectBusiness('{{ $business->id }}')" class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-all font-semibold text-xs cursor-pointer">
                                        Reject
                                    </button>
                                    <button onclick="viewBusiness('{{ $business->id }}')" class="px-3 py-1.5 text-slate-400 hover:text-slate-900 rounded-xl transition-all font-semibold text-xs cursor-pointer">
                                        View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-slate-400 font-semibold">
                                No pending businesses found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer / Pagination Controls --}}
        <div class="p-5 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center">
            <p class="text-xs text-slate-400 font-bold tracking-widest uppercase">Showing 1 of 5 Requests</p>
            <div class="flex gap-2">
                <button class="p-2 rounded-lg border border-slate-200 bg-white text-slate-400 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button class="p-2 rounded-lg border border-slate-200 bg-white text-slate-600 hover:border-brand-500 transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </div>
</main>

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
            tab.classList.remove('text-brand-600', 'border-brand-600', 'border-b-2');
            tab.classList.add('text-slate-400');
        });

        const activeTab = document.querySelector(`[data-status="${status}"]`);
        if(activeTab) {
            activeTab.classList.remove('text-slate-400');
            activeTab.classList.add('text-brand-600', 'border-b-2', 'border-brand-600');
        }

        // Fetch filtered records
        axios.get(`/api/admin/businesses?status=${status}`)
            .then(response => {
                const businesses = response.data.businesses;
                const tbody = document.getElementById('businessTableBody');
                tbody.innerHTML = '';

                if (!businesses || businesses.length === 0) {
                    tbody.innerHTML = `<tr>
                        <td colspan="4" class="text-center py-12 text-slate-400 font-semibold">
                            No ${status} businesses found
                        </td>
                    </tr>`;
                    return;
                }

                businesses.forEach(business => {
                    tbody.innerHTML += `
                        <tr class="group hover:bg-slate-50/80 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 font-bold flex items-center justify-center text-xs border border-brand-100 shrink-0">
                                        ${business.owner.name.slice(0, 2).toUpperCase()}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">${business.owner.name}</p>
                                        <p class="text-xs text-slate-400 italic">${business.owner.email}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[11px] font-bold rounded-lg uppercase tracking-wider">
                                    ${business.name}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                                ${new Date(business.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    ${status === 'pending' ? `
                                        <button onclick="approveBusiness('${business.id}')" class="px-3 py-1.5 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all font-semibold text-xs cursor-pointer">Approve</button>
                                        <button onclick="rejectBusiness('${business.id}')" class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 rounded-xl transition-all font-semibold text-xs cursor-pointer">Reject</button>
                                    ` : ''}
                                    <button onclick="viewBusiness('${business.id}')" class="px-3 py-1.5 text-slate-400 hover:text-slate-900 rounded-xl transition-all font-semibold text-xs cursor-pointer">View</button>
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

</body>
</html>

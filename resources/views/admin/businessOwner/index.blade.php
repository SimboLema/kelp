<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KELP | Admin Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom UI Tweaks to keep the 'Boss-approved' look */
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .sidebar-item-active { border-left: 4px solid #f97316; background: #fff7ed; color: #ea580c; font-weight: 700; }
        .stat-card:hover { transform: translateY(-4px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
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
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main Menu</p>

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

            <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-3.5 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m16 6 4 14H4L8 6"/><path d="M12 6V2"/><path d="M9 2h6"/></svg>
                Categories
            </a>
        </nav>

        <div class="p-6 border-t border-slate-100">
            <form action="#" method="POST">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 bg-orange-500 text-white font-semibold py-3 rounded-xl hover:bg-orange-600 transition-all shadow-lg active:scale-95">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="h-20 glass border-b border-slate-200 px-10 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Overview Dashboard</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Welcome back, Admin</p>
            </div>
            <div class="flex items-center gap-2.5 px-4 py-1.5 bg-white border border-slate-200 rounded-full shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">System Live</span>
            </div>
        </header>

        <main class="p-10 space-y-8">
            <div class="flex flex-col md:flex-row justify-between items-end gap-4">
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

            <div class="flex gap-4 border-b border-slate-200">
                <button class="pb-4 px-2 text-sm font-bold text-orange-600 border-b-2 border-orange-600" data-status="pending" onclick="loadBusinesses('pending')">Pending Requests</button>
                <button class="pb-4 px-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors" data-status="approved" onclick="loadBusinesses('approved')">Approved</button>
                <button class="pb-4 px-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors" data-status="rejected" onclick="loadBusinesses('rejected')">Suspended</button>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Business Owner</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Business Name</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date Applied</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id= "businessTableBody" class="divide-y divide-slate-100">

                        @forelse($businesses as $business)

                        <tr class="group hover:bg-slate-50/80 transition-colors">

                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                                    {{ strtoupper(substr($business->owner->name,0,2)) }}
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

                            <button onclick="approveBusiness('{{ $business->id }}')"
                            class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all">
                            Approve
                            </button>

                            <button onclick="rejectBusiness('{{ $business->id }}')"
                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                            Reject
                            </button>

                            <button onclick="viewBusiness('{{ $business->id }}')"
                            class="p-2 text-slate-400 hover:text-slate-900 rounded-xl transition-all">
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
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>


    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('adminChart');
        if (!ctx) return;

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(249, 115, 22, 0.2)');
        gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                    label: "Restaurants",
                    data: [3, 5, 8, 12, 15, 20],
                    borderColor: '#f97316',
                    borderWidth: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#f97316',
                    pointRadius: 6,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                }
            }
        });
    });



        function approveBusiness(id){

        axios.post(`/api/admin/business/approve/${id}`)
        .then(response => {

        alert(response.data.message)

        location.reload()

        })
        .catch(error=>{
        console.error(error)
        })

        }


        function rejectBusiness(id){

        axios.post(`/api/admin/business/reject/${id}`)
        .then(response => {

        alert(response.data.message)

        location.reload()

        })
        .catch(error=>{
        console.error(error)
        })

        }


        function viewBusiness(id){

        axios.get(`/api/admin/business/view/${id}`)
        .then(response => {

        console.log(response.data.business)

        alert("Business: " + response.data.business.name)

        })
        .catch(error=>{
        console.error(error)
        })

        }

        function loadBusinesses(status) {
            // Update tab styles
            document.querySelectorAll('[data-status]').forEach(tab => {
                tab.classList.remove('text-orange-600','border-orange-600');
                tab.classList.add('text-slate-400');
                tab.classList.remove('border-b-2');
            });
            document.querySelector(`[data-status="${status}"]`).classList.add('text-orange-600','border-b-2','border-orange-600');

            // Fetch businesses
            axios.get(`/api/admin/businesses?status=${status}`)
                .then(response => {
                    const businesses = response.data.businesses;
                    const tbody = document.getElementById('businessTableBody');
                    tbody.innerHTML = ''; // clear current rows

                    if (businesses.length === 0) {
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
                ${business.owner.name.slice(0,2).toUpperCase()}
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
            ${new Date(business.created_at).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' })}
        </td>
        <td class="px-8 py-6">
        <div class="flex items-center justify-center gap-2">
            ${status === 'pending' ? `<button onclick="approveBusiness('${business.id}')" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all">Approve</button>
            <button onclick="rejectBusiness('${business.id}')" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-all">Reject</button>` : ''}
            <button onclick="viewBusiness('${business.id}')" class="p-2 text-slate-400 hover:text-slate-900 rounded-xl transition-all">View</button>
        </div>
        </td>
        </tr>
                        `;
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

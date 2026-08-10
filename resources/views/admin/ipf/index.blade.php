@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                IPF Plans
            </h3>

            <p class="text-muted mb-0">
                Manage Insurance Premium Financing plans
            </p>
        </div>

        <a href="{{ url('/ipf/plans/summary') }}"
           class="btn btn-primary">
            Dashboard Summary
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Filters --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ url('/ipf/plans') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Reference number or registration number"
                               value="{{ request('search') }}">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Statuses
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="completed"
                                {{ request('status') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="defaulted"
                                {{ request('status') === 'defaulted' ? 'selected' : '' }}>
                                Defaulted
                            </option>

                        </select>

                    </div>


                    {{-- Overdue --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Overdue
                        </label>

                        <div class="form-check mt-2">

                            <input type="checkbox"
                                   name="overdue"
                                   value="1"
                                   class="form-check-input"
                                   id="overdue"
                                   {{ request('overdue') ? 'checked' : '' }}>

                            <label class="form-check-label"
                                   for="overdue">
                                Only overdue
                            </label>

                        </div>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-primary me-2">
                            Search
                        </button>

                        <a href="{{ url('/ipf/plans') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Plans --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    IPF Plans
                </h5>

                <span class="text-muted">
                    {{ $plans->total() }} plans
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Reference</th>

                            <th>Registration</th>

                            <th>Status</th>

                            <th>Outstanding</th>

                            <th>Last Charged</th>

                            <th>Transactions</th>

                            <th>Created</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($plans as $plan)

                        <tr>

                            <td>
                                {{ $plans->firstItem() + $loop->index }}
                            </td>


                            <td>

                                @if($plan->order)

                                    <strong>
                                        {{ $plan->order->reference_no }}
                                    </strong>

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ $plan->order->registration_number ?? 'N/A' }}
                            </td>


                            <td>

                                @if($plan->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @elseif($plan->status === 'completed')

                                    <span class="badge bg-primary">
                                        Completed
                                    </span>

                                @elseif($plan->status === 'defaulted')

                                    <span class="badge bg-danger">
                                        Defaulted
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ ucfirst($plan->status) }}
                                    </span>

                                @endif

                            </td>


                            <td>

                                <strong>
                                    {{ number_format($plan->outstanding_balance, 2) }}
                                </strong>

                            </td>


                            <td>

                                @if($plan->last_charged_date)

                                    {{ \Carbon\Carbon::parse(
                                        $plan->last_charged_date
                                    )->format('d M Y') }}

                                @else

                                    <span class="text-danger">
                                        Never
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="badge bg-light text-dark">
                                    {{ $plan->transactions_count }}
                                </span>

                            </td>


                            <td>

                                {{ $plan->created_at
                                    ? $plan->created_at->format('d M Y')
                                    : 'N/A'
                                }}

                            </td>


                            <td class="text-end">

                                <a href="{{ url('/ipf/plans/' . $plan->id) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-5">

                                <h5 class="text-muted">
                                    No IPF plans found
                                </h5>

                                <p class="text-muted mb-0">
                                    No plans match your current filters.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($plans->hasPages())

            <div class="card-footer bg-white">

                {{ $plans->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
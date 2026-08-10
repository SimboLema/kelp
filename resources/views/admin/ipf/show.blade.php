@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                IPF Plan Details
            </h3>

            <p class="text-muted mb-0">
                {{ $plan->order->reference_no ?? 'N/A' }}
            </p>

        </div>

        <a href="{{ url('/ipf/plans') }}"
           class="btn btn-outline-secondary">

            ← Back to Plans

        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        {{-- Status --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Plan Status
                    </small>

                    <div class="mt-2">

                        @if($plan->status === 'active')

                            <span class="badge bg-success fs-6">
                                Active
                            </span>

                        @elseif($plan->status === 'completed')

                            <span class="badge bg-primary fs-6">
                                Completed
                            </span>

                        @elseif($plan->status === 'defaulted')

                            <span class="badge bg-danger fs-6">
                                Defaulted
                            </span>

                        @else

                            <span class="badge bg-secondary fs-6">
                                {{ ucfirst($plan->status) }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Outstanding --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Outstanding Balance
                    </small>

                    <h4 class="mt-2 mb-0">

                        {{ number_format(
                            $plan->outstanding_balance,
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Last Charged --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Last Charged
                    </small>

                    <h5 class="mt-2 mb-0">

                        @if($plan->last_charged_date)

                            {{ \Carbon\Carbon::parse(
                                $plan->last_charged_date
                            )->format('d M Y') }}

                        @else

                            <span class="text-danger">
                                Never
                            </span>

                        @endif

                    </h5>

                </div>

            </div>

        </div>


        {{-- Transactions --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Transactions
                    </small>

                    <h4 class="mt-2 mb-0">
                        {{ $plan->transactions->count() }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Information --}}
    <div class="row g-4 mb-4">

        {{-- Order --}}
        <div class="col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Order Information
                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tr>

                            <th width="40%">
                                Reference
                            </th>

                            <td>
                                {{ $plan->order->reference_no ?? 'N/A' }}
                            </td>

                        </tr>

                        <tr>

                            <th>
                                Registration
                            </th>

                            <td>
                                {{ $plan->order->registration_number ?? 'N/A' }}
                            </td>

                        </tr>

                        <tr>

                            <th>
                                User ID
                            </th>

                            <td>
                                {{ $plan->order->user_id ?? 'N/A' }}
                            </td>

                        </tr>

                        <tr>

                            <th>
                                Created
                            </th>

                            <td>

                                {{ $plan->created_at
                                    ? $plan->created_at->format('d M Y H:i')
                                    : 'N/A'
                                }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>


        {{-- IPF --}}
        <div class="col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        IPF Information
                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tr>

                            <th width="40%">
                                Plan ID
                            </th>

                            <td>
                                {{ $plan->id }}
                            </td>

                        </tr>

                        <tr>

                            <th>
                                Status
                            </th>

                            <td>
                                {{ ucfirst($plan->status) }}
                            </td>

                        </tr>

                        <tr>

                            <th>
                                Outstanding
                            </th>

                            <td>

                                <strong>
                                    {{ number_format(
                                        $plan->outstanding_balance,
                                        2
                                    ) }}
                                </strong>

                            </td>

                        </tr>

                        <tr>

                            <th>
                                Last Charged
                            </th>

                            <td>

                                {{ $plan->last_charged_date
                                    ? \Carbon\Carbon::parse(
                                        $plan->last_charged_date
                                    )->format('d M Y')
                                    : 'Never'
                                }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>


    {{-- Admin Actions --}}
    @if($plan->status === 'active')

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Admin Actions
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    {{-- Payment --}}
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <h6 class="mb-3">
                                Record Payment
                            </h6>

                            <form method="POST"
                                  action="{{ url(
                                      '/ipf/plans/' .
                                      $plan->id .
                                      '/payments'
                                  ) }}">

                                @csrf

                                <div class="mb-3">

                                    <label class="form-label">
                                        Amount
                                    </label>

                                    <input type="number"
                                           name="amount"
                                           class="form-control"
                                           step="0.01"
                                           min="0.01"
                                           max="{{ $plan->outstanding_balance }}"
                                           required>

                                </div>


                                <div class="mb-3">

                                    <label class="form-label">
                                        Payment Note
                                    </label>

                                    <textarea name="note"
                                              class="form-control"
                                              rows="3"
                                              placeholder="e.g. Cash payment received at branch"
                                              required></textarea>

                                </div>


                                <button type="submit"
                                        class="btn btn-success">

                                    Record Payment

                                </button>

                            </form>

                        </div>

                    </div>


                    {{-- Default --}}
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <h6 class="mb-3 text-danger">
                                Mark as Defaulted
                            </h6>

                            <form method="POST"
                                  action="{{ url(
                                      '/ipf/plans/' .
                                      $plan->id .
                                      '/default'
                                  ) }}">

                                @csrf

                                <div class="mb-3">

                                    <label class="form-label">
                                        Reason
                                    </label>

                                    <textarea name="reason"
                                              class="form-control"
                                              rows="3"
                                              placeholder="Reason for marking this plan as defaulted"
                                              required></textarea>

                                </div>


                                <button type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to mark this plan as defaulted?')">

                                    Mark Defaulted

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Transactions --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Transaction History
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Date</th>
                            <th>Note</th>
                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($plan->transactions as $transaction)

                        <tr>

                            <td>
                                {{ $transaction->id }}
                            </td>


                            <td>

                                @if($transaction->type === 'penalty')

                                    <span class="badge bg-danger">
                                        Penalty
                                    </span>

                                @elseif($transaction->type === 'installment')

                                    <span class="badge bg-success">
                                        Installment
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ ucfirst($transaction->type) }}
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ number_format(
                                    $transaction->amount,
                                    2
                                ) }}
                            </td>


                            <td>
                                {{ number_format(
                                    $transaction->balance_after,
                                    2
                                ) }}
                            </td>


                            <td>

                                {{ \Carbon\Carbon::parse(
                                    $transaction->transaction_date
                                )->format('d M Y') }}

                            </td>


                            <td>
                                {{ $transaction->note ?? '-' }}
                            </td>


                            <td>

                                @if(
                                    $transaction->type === 'penalty' &&
                                    $transaction->amount > 0 &&
                                    $plan->status === 'active'
                                )

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#waivePenalty{{ $transaction->id }}">

                                        Waive

                                    </button>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>


                        {{-- Waive Modal --}}
                        @if(
                            $transaction->type === 'penalty' &&
                            $transaction->amount > 0 &&
                            $plan->status === 'active'
                        )

                            <div class="modal fade"
                                 id="waivePenalty{{ $transaction->id }}"
                                 tabindex="-1"
                                 aria-hidden="true">

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Waive Penalty
                                            </h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                            </button>

                                        </div>


                                        <form method="POST"
                                              action="{{ url(
                                                  '/ipf/plans/' .
                                                  $plan->id .
                                                  '/transactions/' .
                                                  $transaction->id .
                                                  '/waive'
                                              ) }}">

                                            @csrf

                                            <div class="modal-body">

                                                <div class="alert alert-warning">

                                                    You are about to waive a penalty of

                                                    <strong>
                                                        {{ number_format(
                                                            $transaction->amount,
                                                            2
                                                        ) }}
                                                    </strong>.

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Reason
                                                    </label>

                                                    <textarea name="reason"
                                                              class="form-control"
                                                              rows="4"
                                                              required
                                                              placeholder="Explain why this penalty is being waived"></textarea>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">

                                                    Cancel

                                                </button>

                                                <button type="submit"
                                                        class="btn btn-danger">

                                                    Waive Penalty

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5 text-muted">

                                No transactions found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
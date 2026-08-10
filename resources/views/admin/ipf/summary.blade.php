@extends('layouts.admin')


@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                IPF Dashboard
            </h3>

            <p class="text-muted mb-0">
                Insurance Premium Financing overview
            </p>

        </div>

        <a href="{{ url('/ipf/plans') }}"
           class="btn btn-primary">

            View All Plans

        </a>

    </div>


    {{-- Statistics --}}
    <div class="row g-4 mb-4">

        {{-- Active --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Active Plans
                    </p>

                    <h2 class="mb-0">
                        {{ number_format(
                            $summary['active_count']
                        ) }}
                    </h2>

                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Completed
                    </p>

                    <h2 class="mb-0">
                        {{ number_format(
                            $summary['completed_count']
                        ) }}
                    </h2>

                </div>

            </div>

        </div>


        {{-- Defaulted --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Defaulted
                    </p>

                    <h2 class="mb-0">
                        {{ number_format(
                            $summary['defaulted_count']
                        ) }}
                    </h2>

                </div>

            </div>

        </div>


        {{-- Overdue --}}
        <div class="col-md-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Overdue
                    </p>

                    <h2 class="mb-0">
                        {{ number_format(
                            $summary['overdue_count']
                        ) }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- Outstanding --}}
    <div class="row g-4">

        <div class="col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <p class="text-muted mb-2">
                        Total Outstanding Balance
                    </p>

                    <h1 class="mb-2">

                        {{ number_format(
                            $summary['total_outstanding'],
                            2
                        ) }}

                    </h1>

                    <p class="text-muted mb-0">

                        Total amount currently outstanding
                        across active IPF plans.

                    </p>

                </div>

            </div>

        </div>


        {{-- Quick Actions --}}
        <div class="col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Quick Actions
                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-flex gap-2 flex-wrap">

                        <a href="{{ url('/ipf/plans') }}"
                           class="btn btn-primary">

                            All Plans

                        </a>


                        <a href="{{ url('/ipf/plans?status=active') }}"
                           class="btn btn-outline-success">

                            Active Plans

                        </a>


                        <a href="{{ url('/ipf/plans?overdue=1') }}"
                           class="btn btn-outline-warning">

                            Overdue Plans

                        </a>


                        <a href="{{ url('/ipf/plans?status=defaulted') }}"
                           class="btn btn-outline-danger">

                            Defaulted Plans

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@extends('layouts.admin.app')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

            <div class="row">
                <div class="col-6 col-lg-6 col-xxl-6 d-flex">
                    <div class="w-100">
                        <div class="row">
                            <div class="col-sm-6">
                                {{-- Card #1: Average Response Time --}}
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mt-0">
                                                <h5 class="card-title">Average Response Time</h5>
                                            </div>
                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle" data-feather="truck"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h1 class="mt-1 mb-3">
                                            {{ $averageResponseTime / 60 }}
                                        </h1>
                                        <div class="mb-0">
                                            <span class="text-danger"><i class="mdi mdi-arrow-bottom-right"></i></span>
                                            <span class="text-muted">Minutes</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card #2: Completion Rate --}}
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mt-0">
                                                <h5 class="card-title">Completion Rate</h5>
                                            </div>
                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle" data-feather="users"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h1 class="mt-1 mb-3">
                                            {{ $completionRate }}%
                                        </h1>
                                        <div class="mb-0">
                                            <span class="text-danger">
                                                <i class="mdi mdi-arrow-bottom-right"></i>{{ $notAssignedWorkOrders }}
                                            </span>
                                            <span class="text-muted">Work Orders Not Completed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                {{-- Card #3: Open Work Orders (replaces Earnings) --}}
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mt-0">
                                                <h5 class="card-title">Open Work Orders</h5>
                                            </div>
                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle" data-feather="alert-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h1 class="mt-1 mb-3">
                                            {{ $openWorkOrders }}
                                        </h1>
                                        <div class="mb-0">
                                            <span class="text-success">
                                                <i class="mdi mdi-arrow-bottom-right"></i>
                                            </span>
                                            <span class="text-muted">Currently Open</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card #4: Total Work Orders (replaces Total Earning) --}}
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mt-0">
                                                <h5 class="card-title">Total Work Orders</h5>
                                            </div>
                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle" data-feather="clipboard"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h1 class="mt-1 mb-3">
                                            {{ $totalWorkOrders }}
                                        </h1>
                                        <div class="mb-0">
                                            <span class="text-danger">
                                                <i class="mdi mdi-arrow-bottom-right"></i>
                                            </span>
                                            <span class="text-muted">All-Time</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- row -->
                    </div>
                </div>

                {{-- Right Column: Monthly Work Orders (replaces Monthly Revenue) --}}
                <div class="col-6 col-lg-6 col-xxl-6 d-flex">
                    <div class="card flex-fill w-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Monthly WorkOrders</h5>
                        </div>
                        <div class="card-body d-flex w-100">
                            <div class="align-self-center chart chart-lg">
                                <canvas id="chartjs-dashboard-bar"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          {{-- Latest Work Orders & Monthly WorkOrders bar chart --}}
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Latest Work Orders</h5>
                        </div>
                        <table class="table table-hover my-0">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th class="d-none d-xl-table-cell">Priority</th>
                                <th>Status</th>
                                <th class="d-none d-md-table-cell">Service</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($latestWorkOrders as $workOrder)
                                <tr>
                                    <td>{{ $workOrder->wo_number }}</td>
                                    <td class="d-none d-xl-table-cell">{{ $workOrder->priority }}</td>
                                    <td class="d-none d-xl-table-cell">{{ $workOrder->current_status }}</td>
                                    <td class="d-none d-md-table-cell">{{ $workOrder->service->name }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>




            </div>

        </div>
    </main>
    {{-- Embed chart data --}}
    <script>
        // Convert your Laravel arrays to JavaScript arrays
        let monthlyLabels = @json($monthlyLabels);
        let monthlyData   = @json($monthlyWorkOrders);

        document.addEventListener('DOMContentLoaded', function () {
            // Get the canvas
            const ctx = document.getElementById('chartjs-dashboard-bar').getContext('2d');

            // Create the chart
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Monthly Work Orders',
                        backgroundColor: '#3b5998',
                        borderColor: '#3b5998',
                        hoverBackgroundColor: '#5b7bb8',
                        data: monthlyData
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            // Customize X Axis as needed
                            grid: { display: false }
                        },
                        y: {
                            // Customize Y Axis as needed
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });
        });
    </script>
    {{-- Embed chart data --}}
@endsection

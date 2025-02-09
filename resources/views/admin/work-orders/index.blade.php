@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Work Orders</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title
                        ">Work Orders</h5>
                        <h6 class="card-subtitle text-muted">List of all work orders</h6>
                    </div>
                    <div class="card-body">
                        <a class="btn btn-primary mb-3" href="{{route('work-orders.create')}}">Add New Work Order</a>
                        <table id="datatables-buttons" class="table table-striped" style="width:100%">
                            <thead>
                            <tr>
                                <th>Work Order #</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Approved</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($workOrders as $workOrder)
                                <tr>
                                    <td>{{$workOrder->wo_number}}</td>
                                    <td>@if($workOrder->priority === 'high')
                                            <span class="badge bg-danger">High</span>
                                        @elseif($workOrder->priority === 'medium')
                                            <span class="badge bg-warning text-dark">Medium</span>
                                        @elseif($workOrder->priority === 'low')
                                            <span class="badge bg-success">Low</span>
                                        @endif
                                        @if($workOrder->is_emergency)
                                            <span class="badge badge-danger">Emergency</span>
                                        @endif
                                        @if($workOrder->is_urgent)
                                            <span class="badge badge-warning">Urgent</span>
                                        @endif
                                        @if($workOrder->is_fixed)
                                            <span class="badge badge-success">Fixed</span>
                                        @endif
                                        @if($workOrder->is_reoccurring)
                                            <span class="badge badge-info">Reoccurring</span>
                                        @endif
                                        @if($workOrder->is_scheduled)
                                            <span class="badge badge-primary">Scheduled</span>
                                        @endif
                                        @if($workOrder->is_unscheduled)
                                            <span class="badge badge-secondary">Unscheduled</span>
                                        @endif
                                        @if($workOrder->is_unplanned)
                                            <span class="badge badge-dark">Unplanned</span>
                                        @endif
                                        @if($workOrder->is_planned)
                                            <span class="badge badge-light">Planned</span>
                                        @endif
                                        @if($workOrder->is_in_progress)
                                            <span class="badge badge-info">In Progress</span>
                                        @endif
                                        @if($workOrder->is_on_hold)
                                            <span class="badge badge-warning">On Hold</span>
                                        @endif
                                        @if($workOrder->is_completed)
                                            <span class="badge badge-success">Completed</span>
                                        @endif
                                        @if($workOrder->is_canceled)
                                            <span class="badge badge-danger">Canceled</span>
                                        @endif
                                        @if($workOrder->is_closed)
                                            <span class="badge badge-dark">Closed</span>
                                        @endif
                                        @if($workOrder->is_open)
                                            <span class="badge badge-primary">Open</span>
                                        @endif
                                        @if($workOrder->is_approved)
                                            <span class="badge badge-success">Approved</span>
                                        @endif
                                        @if($workOrder->is_disapproved)
                                            <span class="badge badge-danger">Disapproved</span>
                                        @endif
                                    </td>
                                    <td>{{$workOrder->current_status}}</td>
                                    <td>{{$workOrder->approval_status}}</td>
                                    <td>
                                        <a href="{{route('work-orders.show', $workOrder->id)}}" class="btn btn-primary btn-sm">View</a>
                                        <a href="{{route('work-orders.edit', $workOrder->id)}}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{route('work-orders.destroy', $workOrder->id)}}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                        </form>
                                        @if($workOrder->approval_status === 'pending')
                                        <a href="{{route('workorder.approve',$workOrder)}}" class="btn btn-info btn-sm">Approve</a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

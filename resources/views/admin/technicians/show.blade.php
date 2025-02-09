@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Technicians</h1>
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h5 class="card-title">Technician Details</h5>
                        <h6 class="card-subtitle text-muted">View technician details</h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{asset('/img/avatars/avatar-2.jpg')}}" alt="{{$technician->full_name}}" class="img-fluid rounded-circle mb-2" width="128" height="128">
                        <h5 class="card-title mb-0">{{$technician->full_name}}</h5>
                        <div class="text-muted mb-2">{{$technician->address}}</div>

                        <div>
                            @if($technician->status == 'active')
                                <form action="{{route('technician.activate',$technician)}}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Deactivate Account</button>
                                </form>
                            @else
                                <form action="{{route('technician.deactivate',$technician)}}" method="POST">
                                    @csrf
                                    <button class="btn btn-primary btn-smm">Activate Account</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <hr class="my-0">

                    <div class="card-body">
                        <table class="table table-sm mt-2 mb-4">
                            <tbody>

                            <tr>
                                <th>Email</th>
                                <td>{{$technician->email}}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{$technician->phone}}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{$technician->address}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-8">
<div class="card">
                    <div class="card-header">
                        <h5 class="card-title
                        ">Work Orders Summery</h5>
                        <h6 class="card-subtitle text-muted">View technician work orders</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mt-2 mb-4">
                            <tbody>
                            <tr>
                                <th>Work Orders</th>
                                <td>{{$technician->workOrders->count()}}</td>
                            </tr>
                            <tr>
                                <th>Completed</th>
                                <td>{{$technician->workOrders->where('status','completed')->count()}}</td>
                            </tr>
                            <tr>
                                <th>Pending Visit</th>
                                <td>{{$technician->workOrders->where('status','pending')->count()}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title
                        ">Work Orders History</h5>
                        <h6 class="card-subtitle text-muted">View technician work orders</h6>
                    </div>
                    <div class="card-body">
                        <ul class="timeline mt-2 mb-0">
                            @if($technician->workOrders->isEmpty())
                                <li class="timeline-item">
                                    <strong>No work orders yet.</strong>
                                </li>
                            @else
                                @foreach($technician->workOrders as $order)
                                    <li class="timeline-item">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">WO -{{$order->wo_number}} - {{$order->client->name}}</h6>
                                                <p class="mb-0">{{$order->description}}</p>
                                            </div>
                                            <div class="text-muted
                                            ">{{$order->status}}</div>
                                        </div>
                                        <small class="text-muted
                                        ">Created on {{$order->created_at->format('d M Y')}}</small>
                                    </li>
                                @endforeach


                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

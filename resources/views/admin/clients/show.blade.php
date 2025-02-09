@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Client Profile</h1>
        <div class="row">
            <div class="col-4">
                <div class="card">
                <div class="card-body text-center">
                    <img src="{{$client->logo_url}}" alt="Christina Mason" class="img-fluid rounded-circle mb-2" width="128" height="128">
                    <h5 class="card-title mb-0">{{$client->name}}</h5>
                    <div class="text-muted mb-2">{{$client->sector}}</div>

                    <div>
                        @if($client->status == 'active')
                            <form action="{{route('client.deactivate',$client)}}" method="POST">
                                @csrf
                                <button class="btn btn-danger btn-sm">Deactivate Account</button>
                            </form>
                        @else
                            <form action="{{route('client.activate',$client)}}" method="POST">
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
                                    <td>{{$client->email}}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{$client->phone}}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{$client->address}}</td>
                                </tr>
                                <tr>
                                    <th>Operation Hours</th>
                                    <td>{{ \Carbon\Carbon::parse($client->operation_hours_from)->format('g:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($client->operation_hours_to)->format('g:i') }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <strong>Work Orders History</strong>
                        <ul class="timeline mt-2 mb-0">
                            @if($client->workOrders->isEmpty())
                                <li class="timeline-item">
                                    <strong>No work orders yet.</strong>
                                </li>
                            @else
                                @foreach($client->workOrders as $order)
                                    <li class="timeline-item">
                                        <strong>{{$order->wo_number}}</strong>
                                        <span class="float-end text-muted text-sm">{{$order->created_at->diffForHumans()}}</span>
                                        <p>{{$order->description}}</p>
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

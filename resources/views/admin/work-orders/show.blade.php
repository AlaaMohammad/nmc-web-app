@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Work Order Details</h1>
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">

                    </div>
                    <div class="card-body">
                        <h6>WO - {{$workOrder->wo_number}}</h6>
                                <span class="badge bg-primary">Created {{$workOrder->created_at->diffForHumans()}}</span>
                                <span class="badge bg-primary">Updated {{$workOrder->updated_at->diffForHumans()}}</span>
                    </div>
                    <div class="card-body">
                            <table class="table table-sm mt-2 mb-4">
                                <tbody>
                                <tr>
                                    <th>Work Order Number</th>
                                    <td>{{$workOrder->wo_number}}</td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td>{{$workOrder->client->name}}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{$workOrder->client->email}}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{$workOrder->client->phone}}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge bg-success">{{$workOrder->current_status}}</span></td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
<div class="card-body">
                    <h6>Technician Report</h6>
                        <p>{{$workOrder->technician_report}}</p>
</div>
                    <div class="card-body">
                        <h6>Work Order Images</h6>
                        @if($workOrder->images->isEmpty())
                            <p>No images uploaded</p>
                        @else
                        <div class="row">
                            @foreach($workOrder->images as $image)
                                <div class="col-4">
                                    <img src="{{$image->wo_image_path}}" class="img-fluid">
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6>Work Order Materials and Equipments</h6>
                        @if($workOrder->materials->isEmpty())
                            <p>No Materials Selected</p>
                        @else
                        <div class="row">
                            <table class="table table-sm mt-2 mb-4">
                                <thead>
                                <tr>
                                    <th>Material Name</th>
                                    <th>Material Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($workOrder->workOrderMaterials as $item)
                                    <tr>
                                        <td>{{$item->material_name}}</td>
                                        <td>{{$item->quantity}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6>Visit History</h6>
                        @if($workOrder->workOrderVisits->isEmpty())
                            <p>No visits yet</p>
                        @else
                            <table class="table table-sm mt-2 mb-4">
                                <thead>
                                <tr>
                                    <th>Visit Date</th>
                                    <th>Check-in Time</th>
                                    <th>Check-out Time</th>
                                    <th>Visit Duration</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($workOrder->workOrderVisits as $visit)
                                    <tr>
                                        <td>{{$visit->visit_number}}</td>
                                        <td>{{$visit->checkin_time}}</td>
                                        <td>{{$visit->checkout_time}}</td>
                                        <td>{{$visit->duration}}</td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                    </div>
                    </div>
                </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <strong>Activity</strong>
                        <ul class="timeline mt-2 mb-0">
                            @if($workOrder->workOrderLogs->isEmpty())
                                <li class="timeline-item">
                                    <strong>No activity yet</strong>
                                </li>
                            @else
                                @foreach($workOrder->workOrderLogs as $activity)
                                    <li class="timeline-item">
                                        <strong>{{$activity->action}}</strong>
                                        <span class="float-end text-muted text-sm">{{$activity->created_at->diffForHumans()}}</span>
                                        <p>{{$activity->created_by}}</p>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                </div>
            </div>
            </div>
            </div>
        </div>
    </div>
@endsection


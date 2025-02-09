@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Wordk Order Logs</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title
                        ">Wordk Order Logs</h5>
                        <h6 class="card-subtitle text-muted">List of all wordk order logs</h6>
                    </div>
                    <div class="card-body">
                        <table id="datatables-buttons" class="table table-striped" style="width:100%">
                            <thead>
                            <tr>
                                <th>Wordk Order Log ID</th>
                                <th>Wordk Order Log Name</th>
                                <th>Wordk Order Log Email</th>
                                <th>Wordk Order Log Phone</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($wordkOrderLogs as $wordkOrderLog)
                                <tr>
                                    <td>{{$wordkOrderLog->id}}</td>
                                    <td>{{$wordkOrderLog->name}}</td>
                                    <td>{{$wordkOrderLog->email}}</td>
                                    <td>{{$wordkOrderLog->phone}}</td>
                                    <td>
                                        <a href="{{route('wordk-order-logs.show', $wordkOrderLog->id)}}" class="btn btn-primary btn-sm">View</a>
                                        <a href="{{route('wordk-order-logs.edit', $wordkOrderLog->id)}}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{route('wordk-order-logs.destroy', $wordkOrderLog->id)}}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                        </form>
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

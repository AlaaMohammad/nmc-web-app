@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Clients</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Clients</h5>
                        <h6 class="card-subtitle text-muted">List of all clients</h6>
                        <a href="{{route('clients.create')}}" class="btn btn-primary float-end">Add New Client</a>
                    </div>
                    <div class="card-body">
                        <table id="datatables-buttons" class="table table-striped" style="width:100%">
                            <thead>
                            <tr>
                                <th>Client ID</th>
                                <th>Client Name</th>
                                <th>Client Email</th>
                                <th>Client Phone</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td>{{$client->id}}</td>
                                    <td>{{$client->name}}</td>
                                    <td>{{$client->email}}</td>
                                    <td>{{$client->phone}}</td>
                                    <td>
                                        <a href="{{route('clients.show', $client->id)}}" class="btn btn-primary btn-sm">View</a>
                                        <a href="{{route('clients.edit', $client->id)}}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{route('clients.destroy', $client->id)}}" method="POST" style="display: inline-block">
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

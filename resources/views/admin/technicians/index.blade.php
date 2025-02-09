@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Technicians</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Technicians</h5>
                        <h6 class="card-subtitle text-muted">List of all technicians</h6>
                    </div>
                    <div class="card-body">
                        <table id="datatables-buttons" class="table table-striped" style="width:100%">
                            <thead>
                            <tr>
                                <th>Technician ID</th>
                                <th>Technician Name</th>
                                <th>Technician Email</th>
                                <th>Technician Phone</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($technicians as $technician)
                                <tr>
                                    <td>{{$technician->id}}</td>
                                    <td>{{$technician->full_name}}</td>
                                    <td>{{$technician->email}}</td>
                                    <td>{{$technician->phone}}</td>
                                    <td>
                                        <a href="{{route('technicians.show', $technician->id)}}" class="btn btn-primary btn-sm">View</a>
                                        <a href="{{route('technicians.edit', $technician->id)}}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{route('technicians.destroy', $technician->id)}}" method="POST" style="display: inline-block">
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

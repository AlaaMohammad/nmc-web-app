@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Technicians</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title
                        ">Edit Technician</h5>
                        <h6 class="card-subtitle text-muted">Edit technician details</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{route('technicians.update', $technician->id)}}">
                            @csrf
                            @method('PUT')
                            <div class="form-group
                            ">
                                <label for="name">Technician Name</label>
                                <input type="text" class="form-control" id="name" name="full_name" value="{{$technician->full_name}}">
                            </div>
                            <div class="form-group
                            ">
                                <label for="email">Technician Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{$technician->email}}">
                            </div>
                            <div class="form-group">
                                <label for="phone">Technician Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{$technician->phone}}">
                            </div>
                            <div class="form-group
                            ">
                                <label for="address">Technician Address</label>
                                <textarea class="form-control" id="address" name="address">{{$technician->address}}</textarea>
                            </div>
                            <div class="form-group
                            ">
                                <label for="state">Technician State</label>
                                <input type="text" class="form-control" id="state" name="state" value="{{$technician->state}}">
                            </div>
                            <div class="form-group">
                                <label for="zip">Technician Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="{{$technician->country}}">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Technician</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

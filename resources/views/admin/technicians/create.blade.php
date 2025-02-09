@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Create Technician</h1>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title
                        ">Create Technician</h5>
                        <h6 class="card-subtitle text-muted">Create a new technician</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{route('technicians.store')}}">
                            @csrf
                            <div class="form-group
                            ">
                                <label for="name">Technician Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group
                            ">
                                <label for="email">Technician Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group
                            ">
                                <label for="phone">Technician Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <button type="submit" class="btn btn-primary">Create Technician</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

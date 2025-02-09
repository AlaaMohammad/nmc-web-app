@extends('layouts.app')
@section('content')
    <h1 class="mb-5">Workorders List</h1>
    <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">WO #</th>
                <th scope="col">PRIORITY</th>
                <th scope="col">ASSIGNED</th>
                <th scope="col">CLIENT</th>
                <th scope="col">LOCATION</th>
                <th scope="col">TRADE</th>
                <th scope="col">STATUS</th>
                <th scope="col">REASON</th>
                <th scope="col">DUE BY</th>
                <th scope="col">ETA</th>
                <th scope="col">TIMESLOT</th>

            </tr>
            </thead>
            <tbody>
            @foreach($workorders as $workorder)
                    <tr>
                        <td>{{ $workorder->wo_number }}</td>
                        <td>{{ $workorder->priority }}</td>
                        <td>{{ $workorder->assigned }}</td>
                        <td>{{ $workorder->client }}</td>
                        <td>{{ $workorder->location }}</td>
                        <td>{{ $workorder->trade }}</td>
                        <td>{{ $workorder->status }}</td>
                        <td>{{ $workorder->reason }}</td>
                        <td>{{ $workorder->due_by }}</td>
                        <td>{{ $workorder->eta }}</td>
                        <td>{{ $workorder->timeslot }}</td>
                    </tr>
                    @endforeach
            </tbody>
        </table>


@endsection

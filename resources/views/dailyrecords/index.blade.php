@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header">
            <h3>Daily Record Report</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Male Avg Count</th>
                        <th>Female Avg Count</th>
                        <th>Male Avg Age</th>
                        <th>Female Avg Age</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dailyRecords as $record)
                    <tr>
                        <td>{{ $record['date'] }}</td>
                        <td>{{ $record['male_count'] }}</td>
                        <td>{{ $record['female_count'] }}</td>
                        <td>{{ $record['male_avg_age'] }}</td>
                        <td>{{ $record['female_avg_age'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
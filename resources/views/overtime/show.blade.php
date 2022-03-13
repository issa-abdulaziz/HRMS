@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>{{ $overtime->employee->full_name }}</h3>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  <table class="table table-stripped w-50 mx-auto">
    <thead class="thead-light">
      <tr>
        <th>Key</th>
        <th>Value</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Employee</td>
        <td>{{ $overtime->employee->full_name }}</td>
      </tr>
      <tr>
        <td>Date</td>
        <td>{{ $overtime->date }}</td>
      </tr>
      <tr>
        <td>Time</td>
        <td>{{ floor($overtime->time / 60) }}:{{ $overtime->time % 60 }}</td>
      </tr>
      <tr>
        <td>Rate</td>
        <td>x{{ $overtime->rate }}</td>
      </tr>
      <tr>
        <td>Salary</td>
        <td>{{ $overtime->salary }} {{ session('setting')->currency }}</td>
      </tr>
      <tr>
        <td>Working Hour per day</td>
        <td>{{ $overtime->working_hour }} hr</td>
      </tr>
      <tr>
        <td>Hourly price</td>
        <td>{{ round($overtime->salary / 30 / $overtime->working_hour, 2) }} {{ session('setting')->currency }}</td>
      </tr>
      <tr>
        <td>Amount</td>
        <td>{{ $overtime->amount }}</td>
      </tr>
      <tr>
        <td>Note</td>
        <td>{{ $overtime->note }}</td>
      </tr>
    </tbody>
  </table>
@endsection

@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>{{$advancedPayment->employee->full_name}}</h3>
        <a href="{{url()->previous()}}" class="btn btn-primary">Back</a>
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
                <td>{{$advancedPayment->employee->full_name}}</td>
            </tr>
            <tr>
                <td>Date</td>
                <td>{{$advancedPayment->date}}</td>
            </tr>
            <tr>
                <td>Amount</td>
                <td>{{$advancedPayment->amount}}</td>
            </tr>
            <tr>
                <td>Note</td>
                <td>{{$advancedPayment->note}}</td>
            </tr>
        </tbody>
    </table>
@endsection
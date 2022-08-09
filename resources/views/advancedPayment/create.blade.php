@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add new Advanced Payment</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('advanced-payment.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="employee_id">Employee</label>
                                    <select id="employee_id" class="custom-select" name="employee_id">
                                        <option disabled selected>select employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" value="{{ old('date') ? old('date') : date('Y-m-d') }}"
                                        id="date" name="date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="amount">Amount (in {{ session('setting')->currency }})</label>
                                    <input type="number" value="{{ old('amount') }}" id="amount" name="amount"
                                        class="form-control" min="1" required>
                                </div>
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea class="form-control" name="note" id="note" cols="30" rows="5">{{ old('note') }}</textarea>
                                </div>
                                <button class="btn btn-success btn-block w-50 mx-auto" name="submit" id="submit" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        $('body').on('change', '#employee_id', function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: "{{ route('advanced-payment.getData', ['%id%']) }}".replace('%id%', $('#employee_id')
                    .val()),
                dataType: 'json',
                success: function(response) {
                    $('#date').attr('min', response.hired_at);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });
    </script>
@endpush

@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Add new Advanced Payment</h3>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  <form action="{{ route('advanced-payment.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="employee_id">Employee</label>
      <select id="employee_id" class="custom-select" name="employee_id">
        <option disabled selected>select employee</option>
        @foreach ($employees as $employee)
          <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
            {{ $employee->full_name }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="date">Date</label>
      <input type="date" value="{{ old('date') ? old('date') : date('Y-m-d') }}" id="date" name="date"
        class="form-control" required>
    </div>
    <div class="form-group">
      <label for="amount">Amount (in {{ $setting->currency }})</label>
      <input type="number" value="{{ old('amount') }}" id="amount" name="amount" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="note">Note</label>
      <textarea class="form-control" name="note" id="note" cols="30" rows="5">{{ old('note') }}</textarea>
    </div>
    <button class="btn btn-primary" name="submit" id="submit" type="submit">Submit</button>
  </form>
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
        type: "POST",
        url: "/advanced-payment/getData",
        data: {
          employee_id: $('#employee_id').val()
        },
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

@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Add new Vacation</h3>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  {{-- <div>
    @foreach ($employees as $employee)
      <div id="{{ $employee->id }}">
        <input type="hidden" value="{{ $employee->getVacationDays() }}" class="vacationDays">
        <input type="hidden" value="{{ $employee->getTakingVacationStartAt() }}" class="vacationStartCountAt">
      </div>
    @endforeach
  </div> --}}

  <form action="{{ route('vacation.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="employee_id">Employee</label>
      <select id="employee_id" class="custom-select" name="employee_id" required>
        <option disabled selected>select employee</option>
        @foreach ($employees as $employee)
          <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
            {{ $employee->full_name }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="date_from">Date</label>
      <input type="date" value="{{ old('date_from') ? old('date_from') : date('Y-m-d') }}" id="date_from"
        name="date_from" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="days">Days</label>
      <input type="number" value="{{ old('days') }}" id="days" name="days" class="form-control" min="1" required>
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
    // $('body').on('change', '#employee_id', setAttr);
    // setAttr();

    // function setAttr() {
    //   let employee_id = $('#employee_id').val();
    //   if (employee_id) {
    //     let vacationStartCountAt = $('#' + employee_id + ' .vacationStartCountAt').val();
    //     let vacationDays = $('#' + employee_id + ' .vacationDays').val();
    //     $('#date_from').attr('min', vacationStartCountAt);
    //     $('#days').attr('max', vacationDays);
    //   }
    // }
    $('body').on('change', '#employee_id', function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "POST",
        url: "/vacation/getData",
        data: {
          employee_id: $('#employee_id').val()
        },
        dataType: 'json',
        success: function(response) {
          $('#date_from').attr('min', response.vacationStartCountAt);
          $('#days').attr('max', response.vacationDays);
        },
        error: function(data) {
          console.log('Error:', data);
        }
      });
    });
  </script>
@endpush

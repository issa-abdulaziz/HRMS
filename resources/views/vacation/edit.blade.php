@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Edit Vacation</h3>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  <form action="{{ route('vacation.update', $vacation->id) }}" method="POST">
    @method('PUT')
    @csrf
    <div class="form-group">
      <label for="employee_id">Employee</label>
      <select id="employee_id" class="custom-select" name="employee_id" required>
        <option value="{{ $vacation->employee->id }}" selected>
          {{ $vacation->employee->full_name }}</option>
      </select>
    </div>
    <div class="form-group">
      <label for="date_from">Date</label>
      <input type="date" value="{{ $vacation->date_from }}" id="date_from" name="date_from"
        min="{{ $vacation->employee->taking_vacation_start_at }}" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="days">Days</label>
      <input type="number" value="{{ $vacation->days }}" id="days" name="days" class="form-control" min="1"
        max="{{ $totalVacationDays }}" required>
    </div>
    <div class="form-group">
      <label for="note">Note</label>
      <textarea class="form-control" name="note" id="note" cols="30" rows="5">{{ $vacation->note }}</textarea>
    </div>
    <button class="btn btn-primary" name="submit" id="submit" type="submit">Submit</button>
  </form>
@endsection

@push('script')
  <script>
    // let vacationStartCountAt = $('.vacationStartCountAt').val();
    // let vacationDays = $('.vacationDays').val();
    // let thisVacationDays = $('.thisVacationDays').val();
    // let totalVacationDays = parseInt(vacationDays) + parseInt(thisVacationDays);

    // $('#date_from').attr('min', vacationStartCountAt);
    // $('#days').attr('max', totalVacationDays);
  </script>
@endpush

@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Edit Overtime</h3>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  <form action="{{ route('overtime.update', $overtime->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="employee_id">Employee</label>
      <select id="employee_id" class="custom-select" name="employee_id" required>
        <option disabled selected>select employee</option>
        @foreach ($employees as $employee)
          <option value="{{ $employee->id }}" {{ $overtime->employee_id == $employee->id ? 'selected' : '' }}>
            {{ $employee->full_name }}</option>
        @endforeach
      </select>
      <input type="hidden" value="{{ $overtime->working_hour }}" id="working_hour" name="working_hour" required min="1"
        max="99.999">
    </div>
    <div class="form-group">
      <label for="date">Date</label>
      <input type="date" value="{{ $overtime->date }}" id="date" name="date" min="{{ $overtime->employee->hired_at }}"
        class="form-control" required>
    </div>
    <div class="form-group row">
      <label class="col-sm-12">Time</label>
      <input type="hidden" value="{{ $overtime->time }}" id="time" name="time" required>
      <label for="hour" class="col-sm-2 col-form-label">Hour:</label>
      <div class="col-sm-4">
        <input type="number" min="0" max="24" class="form-control" id="hour" name="hour">
      </div>
      <label for="minutes" class="col-sm-2 col-form-label">Minutes:</label>
      <div class="col-sm-4">
        <input type="number" min="0" max="59" class="form-control" id="minutes" name="minutes">
      </div>
    </div>
    <div class="form-group">
      <label for="rate">Rate</label>
      <input type="number" value="{{ $overtime->rate }}" id="rate" name="rate" min="1.0" max="99.999" step="0.25"
        class="form-control" required>
    </div>
    <div class="form-group">
      <label for="hourly_price">Hourly Price (in {{ $setting->currency }})</label>
      <input type="number" readonly step="0.01"
        value="{{ round($overtime->salary / 30 / $overtime->working_hour, 2) }}" id="hourly_price" name="hourly_price"
        class="form-control" required>
      <input type="hidden" value="{{ $overtime->salary }}" id="salary" name="salary" required>
    </div>
    <div class="form-group">
      <label for="amount">Amount (in {{ $setting->currency }})</label>
      <input type="number" readonly value="{{ $overtime->amount }}" id="amount" name="amount" class="form-control"
        required>
    </div>
    <div class="form-group">
      <label for="note">Note</label>
      <textarea class="form-control" name="note" id="note" cols="30" rows="5">{{ $overtime->note }}</textarea>
    </div>
    <button class="btn btn-primary" name="submit" id="submit" type="submit">Submit</button>
  </form>
@endsection
@push('script')
  <script>
    let time = $('#time').val();
    $('#hour').val(Math.floor(time / 60));
    $('#minutes').val(time % 60);
  </script>
  <script type="text/javascript" src="{{ asset('js/_custom/overtime.js') }}"></script>
@endpush

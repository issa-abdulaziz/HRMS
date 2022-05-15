@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Add new Overtime</h3>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  <form action="{{ route('overtime.store') }}" method="POST">
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
      <input type="hidden" value="{{ old('working_hour') }}" id="working_hour" name="working_hour" required min="1"
        max="99.999">
    </div>
    <div class="form-group">
      <label for="date">Date</label>
      <input type="date" value="{{ old('date') ? old('date') : date('Y-m-d') }}" id="date" name="date"
        class="form-control" required>
    </div>
    <div class="form-group row">
      <label class="col-sm-12">Time</label>
      <input type="hidden" value="{{ old('time') }}" id="time" name="time" required>
      <label for="hour" class="col-sm-2 col-form-label">Hour:</label>
      <div class="col-sm-4">
        <input type="number" value="{{ old('hour') ? old('hour') : 0 }}" min="0" max="24" class="form-control"
          id="hour" name="hour">
      </div>
      <label for="minutes" class="col-sm-2 col-form-label">Minutes:</label>
      <div class="col-sm-4">
        <input type="number" value="{{ old('minutes') ? old('minutes') : 0 }}" min="0" max="59" class="form-control"
          id="minutes" name="minutes">
      </div>
    </div>
    <div class="form-group">
      <label for="rate">Rate</label>
      <input type="number" value="{{ old('rate') }}" id="rate" name="rate" class="form-control" min="1.0"
        max="99.999" step="0.25" required>
    </div>
    <div class="form-group">
      <label for="hourly_price">Hourly Price (in {{ session('setting')->currency }})</label>
      <input type="number" readonly step="0.01" value="{{ old('hourly_price') }}" id="hourly_price" name="hourly_price"
        class="form-control" required>
      <input type="hidden" value="{{ old('salary') }}" id="salary" name="salary" required>
    </div>
    <div class="form-group">
      <label for="amount">Amount (in {{ session('setting')->currency }})</label>
      <input type="number" readonly value="{{ old('amount') }}" id="amount" name="amount" class="form-control"
        required>
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
      let config = {
          routes : {
              hourlyPrice : "{{ route('overtime.getHourlyPrice', ['%employee%']) }}",
              rate : "{{ route('overtime.getRate', ['%date%']) }}",
          }
      }
  </script>
@endpush

@push('script')
  <script type="text/javascript" src="{{ asset('js/_custom/overtime.js') }}"></script>
@endpush

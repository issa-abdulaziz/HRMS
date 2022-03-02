@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Add new employee</h3>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  <form action="{{ route('employee.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="full_name">Full Name</label>
      <input type="text" value="{{ old('full_name') }}" id="full_name" name="full_name" class="form-control" required
        minlength="3" maxlength="50">
    </div>
    <div class="form-group">
      <label for="phone_number">Phone Number</label>
      <input type="text" value="{{ old('phone_number') }}" id="phone_number" name="phone_number" class="form-control"
        required minlength="8" maxlength="50">
    </div>
    <div class="form-group">
      <label for="date_of_birth">Date of Birth</label>
      <input type="date" value="{{ old('date_of_birth') }}" id="date_of_birth" name="date_of_birth"
        class="form-control" required>
    </div>
    <div class="form-group">
      <label for="city">City</label>
      <input type="text" value="{{ old('city') }}" id="city" name="city" class="form-control" required minlength="3"
        maxlength="50">
    </div>
    <div class="form-group">
      <label for="position">Position</label>
      <input type="text" value="{{ old('position') }}" id="position" name="position" class="form-control" required
        minlength="3" maxlength="50">
    </div>
    <div class="form-group">
      <label for="salary">Salary (in {{ $currency }})</label>
      <input type="number" value="{{ old('salary') }}" id="salary" name="salary" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="hired_at">Hired at</label>
      <input type="date" value="{{ old('hired_at') ? old('hired_at') : date('Y-m-d') }}" id="hired_at" name="hired_at"
        class="form-control" required>
    </div>
    <div class="form-group">
      <label for="vacation_start_count_at">Start counting for vacation at</label>
      <input type="month" value="{{ old('vacation_start_count_at') }}" id="vacation_start_count_at"
        name="vacation_start_count_at" class="form-control">
    </div>
    <div class="form-group">
      <div class="custom-control custom-checkbox">
        <input id="active" class="custom-control-input" type="checkbox" name="active"
          {{ old('active') == true ? 'checked' : '' }}>
        <label for="active" class="custom-control-label">Active</label>
      </div>
    </div>
    <div class="form-group">
      <label for="shift_id">Work Shift</label>
      <select id="shift_id" class="custom-select" name="shift_id" required>
        <option disabled selected>select Shift</option>
        @foreach ($shifts as $shift)
          <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
            {{ $shift->title }}</option>
        @endforeach
      </select>
    </div>
    <button class="btn btn-primary" name="submit" id="submit" type="submit">Submit</button>
  </form>
@endsection
@push('script')
  <script>
    $('body').on('change', '#hired_at', setMinDate);

    if ($('#hired_at').val()) {
      setMinDate();
    }

    function setMinDate() {
      let date = new Date($('#hired_at').val());
      $('#vacation_start_count_at').attr('min', date.getFullYear() + '-' + (date.getMonth() + 1));
      checkDateFormat();
    }

    function checkDateFormat() {
      let minDate = $('#vacation_start_count_at').attr('min');
      if (minDate.length == 6) {
        let newMinDate = minDate.substring(0, 5) + '0' + minDate.substring(5);
        $('#vacation_start_count_at').attr('min', newMinDate);
      }
    }
  </script>
@endpush

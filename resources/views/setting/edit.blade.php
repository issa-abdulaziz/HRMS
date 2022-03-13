@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Edit setting</h3>
  </div>
  <form action="{{ route('setting.update', session('setting')->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="weekend">Weekend</label>
      <select class="custom-select" id="weekend" name="weekend" required>
        <option {{ session('setting')->weekend === 'Monday' ? 'selected' : '' }} value="Monday">Monday</option>
        <option {{ session('setting')->weekend === 'Teusday' ? 'selected' : '' }} value="Teusday">Teusday</option>
        <option {{ session('setting')->weekend === 'Wednesday' ? 'selected' : '' }} value="Wednesday">Wednesday</option>
        <option {{ session('setting')->weekend === 'Thursday' ? 'selected' : '' }} value="Thursday">Thursday</option>
        <option {{ session('setting')->weekend === 'Friday' ? 'selected' : '' }} value="Friday">Friday</option>
        <option {{ session('setting')->weekend === 'Saturday' ? 'selected' : '' }} value="Saturday">Saturday</option>
        <option {{ session('setting')->weekend === 'Sunday' ? 'selected' : '' }} value="Sunday">Sunday</option>
      </select>
    </div>
    <div class="form-group">
      <label for="normalOvertimeRate">Normal overtime rate</label>
      <input type="number" value="{{ session('setting')->normal_overtime_rate }}" id="normalOvertimeRate"
        name="normalOvertimeRate" class="form-control" min="1.0" max="99.999" step="0.25" required>
    </div>
    <div class="form-group">
      <label for="weekendOvertimeRate">Weekend overtime rate</label>
      <input type="number" value="{{ session('setting')->weekend_overtime_rate }}" id="weekendOvertimeRate"
        name="weekendOvertimeRate" class="form-control" min="1.0" max="99.999" step="0.25" required>
    </div>
    <div class="form-group">
      <label for="leewayDiscountRate">Leeway Discount Rate</label>
      <input type="number" value="{{ session('setting')->leeway_discount_rate }}" id="leewayDiscountRate"
        name="leewayDiscountRate" class="form-control" min="1.0" max="99.999" step="0.25" required>
    </div>
    <div class="form-group">
      <label for="vacationRate">Vacation Increment Rate per Month</label>
      <input type="number" value="{{ session('setting')->vacation_rate }}" id="vacationRate" name="vacationRate"
        class="form-control" min="1.0" max="99.999" step="0.25" required>
    </div>
    <div class="form-group">
      <label for="takingVacationAllowedAfter">Taking Vacation Allowed After ... Month(s)</label>
      <input type="number" value="{{ session('setting')->taking_vacation_allowed_after }}"
        id="takingVacationAllowedAfter" name="takingVacationAllowedAfter" class="form-control" min="1" max="100"
        required>
    </div>
    <div class="form-group">
      <label for="currency">Currency</label>
      <select id="currency" class="custom-select" name="currency" required>
        <option {{ session('setting')->currency === 'USD' ? 'selected' : '' }} value="USD">USD</option>
        <option {{ session('setting')->currency === 'LBP' ? 'selected' : '' }} value="LBP">LBP</option>
      </select>
    </div>
    <button class="btn btn-primary" name="submit" id="submit" type="submit">Submit</button>
  </form>
@endsection

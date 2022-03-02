@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Add new shit</h3>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  <form action="{{ route('shift.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control" required
        maxlength="50">
    </div>
    <div class="form-group">
      <label for="starting_time">Starting Time</label>
      <input type="time" id="starting_time" name="starting_time" class="form-control"
        value="{{ Carbon\Carbon::now()->format('H:i') }}" required>
    </div>
    <div class="form-group">
      <label for="leaving_time">Leaving Time</label>
      <input type="time" id="leaving_time" name="leaving_time" class="form-control"
        value="{{ Carbon\Carbon::now()->format('H:i') }}" required>
    </div>
    <div class="form-group">
      <div class="custom-control custom-checkbox">
        <input id="across_midnight" class="custom-control-input" type="checkbox" name="across_midnight"
          {{ old('across_midnight') == true ? 'checked' : '' }}>
        <label for="across_midnight" class="custom-control-label">Across Midnight</label>
      </div>
    </div>
    <button class="btn btn-primary" name="submit" id="submit" type="submit">Submit</button>
  </form>
@endsection

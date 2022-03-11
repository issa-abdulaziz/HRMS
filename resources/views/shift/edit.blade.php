@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Edit shift</h3>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  <form action="{{ route('shift.update', $shift->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" value="{{ $shift->title }}" id="title" name="title" class="form-control" required
        minlength="3" maxlength="50">
    </div>
    <div class="form-group">
      <label for="starting_time">Starting Time</label>
      <input type="time" value="{{ Carbon\Carbon::parse($shift->starting_time)->format('H:i') }}" id="starting_time"
        name="starting_time" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="leaving_time">Leaving Time</label>
      <input type="time" value="{{ Carbon\Carbon::parse($shift->leaving_time)->format('H:i') }}" id="leaving_time"
        name="leaving_time" class="form-control" required>
    </div>
    <div class="form-group">
      <div class="custom-control custom-checkbox">
        <input id="across_midnight" class="custom-control-input" type="checkbox" name="across_midnight"
          {{ $shift->across_midnight == true ? 'checked' : '' }}>
        <label for="across_midnight" class="custom-control-label">Across Midnight</label>
      </div>
    </div>
    <button class="btn btn-primary" name="submit" id="submit" type="submit">Submit</button>
  </form>
@endsection

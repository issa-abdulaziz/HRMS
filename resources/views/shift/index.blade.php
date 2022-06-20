@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Work Shifts</h3>
    <a href="/shift/create" class="btn btn-primary">Add New One</a>
  </div>
  @if (count($shifts) > 0)
    <table class="table table-stripped">
      <thead class="thead-light">
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Starting Time</th>
          <th>Leaving Time</th>
          <th>Across Midnight</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($shifts as $shift)
          <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $shift->title }}</td>
            <td>{{ $shift->starting_time }}</td>
            <td>{{ $shift->leaving_time }}</td>
            <td>
              @if ($shift->across_midnight)
              <span class="badge badge-primary mr-1">Accross MidNight</span>
              @else
              <span class="badge badge-warning mr-1">Not Accross MidNight</span>
              @endif
            </td>
            <td><a class="btn btn-success btn-sm" href="{{ route('shift.edit', $shift->id) }}"><i
                  class="fas fa-edit"></i></a>
            </td>
            <td>
              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeletionModal"
                data-id="{{ $shift->id }}" data-name="{{ $shift->title }}">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @include('inc.confirmDeletion', ['title' => 'shift'])
  @else
    <p>No Shifts Yet</p>
  @endif
@endsection
@push('script')
  <script type="text/javascript" src="{{ asset('js/_custom/confirmDeletion.js') }}"></script>
@endpush

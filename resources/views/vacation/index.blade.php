@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Vacations</h3>
    <a href="/vacation/create" class="btn btn-primary">Add New One</a>
  </div>

  <form action="{{ route('vacation.index') }}" method="GET" id="form-filter">
    @csrf
    <input type="month" id="date" name="date" value="{{ $date }}" class="form-control my-3">
  </form>

  @if (count($vacations) > 0)
    <table class="table display nowrap" id="table_id" style="width:100%">
      <thead class="thead-light">
        <tr>
          <th data-priority="1">#</th>
          <th data-priority="3">Full Name</th>
          <th data-priority="4">From</th>
          <th data-priority="5">To</th>
          <th data-priority="6">Days</th>
          <th data-priority="7">Note</th>
          <th data-priority="2"></th>
          <th data-priority="2"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($vacations as $vacation)
          <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td><a
                href="{{ route('employee.show', $vacation->employee->id) }}">{{ $vacation->employee->full_name }}</a>
            </td>
            <td>{{ $vacation->date_from }}</td>
            <td>{{ $vacation->date_to }}</td>
            <td>{{ $vacation->days }}</td>
            <td>{{ $vacation->note }}</td>
            <td><a class="btn btn-success btn-sm" href="{{ route('vacation.edit', $vacation->id) }}"><i
                  class="fas fa-edit"></i></a></td>
            <td>
              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeletionModal"
                data-id="{{ $vacation->id }}" data-name="{{ $vacation->employee->full_name }}"
                data-date="{{ $vacation->date_from }}" data-dateto="{{ $vacation->date_to }}">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @include('inc.confirmDeletion', [
        'title' => 'vacation',
    ])
  @else
    <p>No Vacation Added Yet</p>
  @endif
@endsection
@push('script')
  <script>
    $(document).ready(function() {
      $('#table_id').DataTable({
        dom: 'Bfrtip',
        buttons: [
          'pageLength',
          'copyHtml5',
          'excel',
          'csvHtml5',
          'pdfHtml5',
          'print',
        ],
        lengthMenu: [
          [10, 25, 50, -1],
          ['10 rows', '25 rows', '50 rows', 'Show all']
        ],
        responsive: true,
        scrollY: "400px",
        "scrollCollapse": true,
        "columnDefs": [{
          "orderable": false,
          "searchable": false,
          "targets": [-1, -2]
        }, ],
        "pagingType": "full_numbers",
        language: {
          paginate: {
            next: '>',
            previous: '<',
            first: '<<',
            last: '>>'
          }
        },
        stateSave: true,
      });

      $('#date').change(function() {
        $('#form-filter').submit();
      });
    });
  </script>
  <script type="text/javascript" src="{{ asset('js/_custom/confirmDeletion.js') }}"></script>
@endpush

@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Overtime</h3>
    <a href="/overtime/create" class="btn btn-primary">Add New One</a>
  </div>
  <form action="{{ route('overtime.index') }}" method="GET" id="form-filter">
    @csrf
    <input type="month" id="date" name="date" value="{{ $date }}" class="form-control my-3">
  </form>

  @if (count($overtimes) > 0)
    <table class="table display nowrap" id="table_id" style="width:100%">
      <thead class="thead-light">
        <tr>
          <th data-priority="1">#</th>
          <th data-priority="3">Full Name</th>
          <th data-priority="4">Date</th>
          <th data-priority="5">Time</th>
          <th data-priority="6">Rate</th>
          <th data-priority="7">Amount</th>
          <th data-priority="8">Salary</th>
          <th data-priority="9">Hr/D</th>
          <th data-priority="10">Note</th>
          <th data-priority="2"></th>
          <th data-priority="2"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($overtimes as $overtime)
          <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td><a
                href="{{ route('employee.show', $overtime->employee->id) }}">{{ $overtime->employee->full_name }}</a>
            </td>
            <td>{{ $overtime->date }}</td>
            <td>{{ floor($overtime->time / 60) }}:{{ $overtime->time % 60 }}</td>
            <td>x{{ $overtime->rate }}</td>
            <td>{{ $overtime->amount }} {{ $currency }}</td>
            <td>{{ $overtime->salary }} {{ $currency }}</td>
            <td>{{ $overtime->working_hour }}</td>
            <td>{{ $overtime->note }}</td>
            <td><a class="btn btn-success btn-sm" href="{{ route('overtime.edit', $overtime->id) }}"><i
                  class="fas fa-edit"></i></a></td>
            <td>
              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeletionModal"
                data-id="{{ $overtime->id }}" data-name="{{ $overtime->employee->full_name }}"
                data-date="{{ $overtime->date }}" data-amount=" {{ $overtime->amount }}"
                data-currency="{{ $currency }}">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @include('inc.confirmDeletion', [
        'title' => 'overtime',
    ])
  @else
    <p>No Ovetime Added Yet</p>
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

@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Employees</h3>
    <a href="/employee/create" class="btn btn-primary">Add New One</a>
  </div>
  @if (count($employees) > 0)
    <table class="table display nowrap" id="table_id" style="width:100%">
      <thead class="thead-light">
        <tr>
          <th data-priority="1">#</th>
          <th data-priority="3">Full Name</th>
          <th data-priority="7">Phone Number</th>
          <th data-priority="4">Postion</th>
          <th data-priority="10">Vacation Days</th>
          <th data-priority="9">Vacation Start Count At</th>
          <th data-priority="11">City</th>
          <th data-priority="8">Hired At</th>
          <th data-priority="5">Salary</th>
          <th data-priority="6">Active</th>
          <th data-priority="2"></th>
          <th data-priority="2"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($employees as $employee)
          <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td><a href="{{ route('employee.show', $employee->id) }}">{{ $employee->full_name }}</a></td>
            <td>{{ $employee->phone_number }}</td>
            <td>{{ $employee->position }}</td>
            <td>{{ $employee->getVacationDays() }}</td>
            <td>{{ $employee->vacation_start_count_at }}</td>
            <td>{{ $employee->city }}</td>
            <td>{{ $employee->hired_at }}</td>
            <td>{{ $employee->salary }} {{ session('setting')->currency }}</td>
            <td>
              @if ($employee->active)
                <i class="fa fa-check-square" style="font-size: 18px;color:rgb(0,128,255)"></i>
              @else
                <i class="fa fa-square" style="font-size: 18px;color:rgb(0,128,255)"></i>
              @endif
            </td>
            <td><a class="btn btn-success btn-sm" href="{{ route('employee.edit', $employee->id) }}"><i
                  class="fas fa-edit"></i></a></td>
            <td>
              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmDeletionModal"
                data-id="{{ $employee->id }}" data-name="{{ $employee->full_name }}"
                data-date="{{ $employee->hired_at }}">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @include('inc.confirmDeletion', [
        'title' => 'employee',
    ])
  @else
    <p>No Employee Added Yet</p>
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
    });
    /*
    buttons: [
            {
                extend:    'copyHtml5',
                text:      '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy'
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel'
            },
            {
                extend:    'csvHtml5',
                text:      '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV'
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF'
            }
        ]
        */
  </script>
  <script type="text/javascript" src="{{ asset('js/_custom/confirmDeletion.js') }}" defer></script>
@endpush

@extends('layouts.app')
@section('content')
  <h3>Salary</h3>
  <form action="/salary" method="GET" id="form-filter">
    @csrf
    <input type="month" id="date" name="date" value="{{ $date }}" class="form-control my-3">
  </form>

  <table class="table hover stripe row-border display nowrap" id="table_id" style="width:100%">
    <thead class="thead-light">
      <tr>
        <th data-priority="1">#</th>
        <th data-priority="2">Full Name</th>
        <th>Salary</th>
        <th>Overtime</th>
        <th>Leeway</th>
        <th>Advanced Payment</th>
        <th>Absent Day</th>
        <th data-priority="3">Net Salary</th>
      </tr>
    </thead>
    <tbody>
      @if (count($data) > 0)
        @foreach ($data as $entry)
          <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td><a href="/employee/{{ $entry['employee_id'] }}">{{ $entry['employee_name'] }}</a></td>
            <td>{{ $entry['salary'] }} {{ $currency }}</td>
            <td>{{ $entry['overtimeAmount'] }} {{ $currency }}</td>
            <td>{{ $entry['leewayDiscount'] }} {{ $currency }}</td>
            <td>{{ $entry['advancedPaymentAmount'] }} {{ $currency }}</td>
            <td>{{ $entry['absentDayDiscountAmount'] }} {{ $currency }}</td>
            <td>{{ $entry['netSalary'] }} {{ $currency }}</td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>

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
@endpush

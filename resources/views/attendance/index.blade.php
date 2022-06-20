@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Attendence</h3>
    <button type="button" id="btn-delete" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeletionModal">Delete</button>
  </div>
  <form action="{{ route('attendance.store') }}" method="POST">
    @csrf
    <div class="form-group row my-4">
        <label for="date" class="col-sm-2 col-form-label">Date:</label>
        <div class="col-sm-4">
        <input type="date" class="form-control" name="date" id="date" value="{{ $date ?? now()->format('Y-m-d') }}">
        </div>
        <label for="minutes" class="col-sm-2 col-form-label">Shift:</label>
        <div class="col-sm-4">
        <select name="shift" id="shift" class="custom-select">
            @foreach ($shifts as $shift)
            <option value="{{ $shift->id }}" {{ old('shift') == $shift->id ? 'selected' : '' }}>{{ $shift->title }}</option>
            @endforeach
        </select>
        </div>
    </div>
    @if (count($employees) > 0)
        <table id="attendance_table" class="table table-stripped">
            <thead class="thead-light">
                <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 6%;">Present</th>
                <th style="width: 15%;">Employee</th>
                <th style="width: 15%;">Time-In</th>
                <th style="width: 15%;">Time-Out</th>
                <th style="width: 32%">Note</th>
                <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div id="next-to-table" class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center pl-3">
                <label class="custom-checkbox m-0 pb-1"><input type="checkbox" class="check-all-box"><span
                    class="custom-label"></span></label>
                <p class="m-0">Check All</p>
            </div>
            <div class="d-flex align-items-center pr-3">
                <button id="btn-cancel" class="btn btn-outline-danger mr-2" type="button">cancel</button>
                <button id="btn-save" class="btn btn-primary" type="submit">Submit</button>
            </div>
        </div>
    </form>
    @include('inc.confirmDeletion', array('title'=>'attendance'))
  @else
    <p>No Employee Added Yet</p>
  @endif
@endsection

@push('script')
    <script>
        let starting_time,leaving_time;
        let delete_btn = $('#btn-delete');

        checkAttendance();
        $('#date').change(checkAttendance);
        $('#shift').change(checkAttendance);
        $('#btn-cancel').click(checkAttendance);

        function checkAttendance() {
            delete_btn.attr('data-id', $('#date').val());
            delete_btn.attr('data-date', $('#date').val());
            $.ajax({
                type: "GET",
                url: "{{ route('attendance.check', [':date', ':shift']) }}".replace(':date', $('#date').val()).replace(':shift', $('#shift').val()),
                dataType: 'json',
                success: function(response) {
                    starting_time =  response.shift.starting_time;
                    leaving_time =  response.shift.leaving_time;
                    displayEmployee(response.data);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

        function displayEmployee(data) {
            $('#attendance_table tbody').empty();
            data.forEach((employee, index) => {
                let url = "{{ route('employee.show', '%id%') }}".replace('%id%', employee.employee_id);
                let number = $('<td class="align-middle row-number"></td>').html(index + 1);
                let present = $('<td class="align-middle"></td>').html(
                '<label class="custom-checkbox"><input type="checkbox" name="attendance[' + employee.employee_id + '][present]" ' + (employee.present == 1 ? "checked" : "") + ' class="' + (employee.in_vacation ? "" : "check-box") + '" ' + (employee.in_vacation ? "disabled" : "") + '><span class="custom-label"></span></label>'
                );
                let name = $('<td class="align-middle"></td>').html('<a href="' + url + '">' + employee.full_name + '</a><input type="hidden" name="attendance[' + employee.employee_id + '][employee_id]" value="' + employee.employee_id + '">');
                let time_in = $('<td class="align-middle"></td>').html(
                '<input type="time" name="attendance[' + employee.employee_id + '][time_in]" min="' + starting_time + '" max="' + leaving_time +
                '" value="' + employee.time_in + '" ' + (employee.present == 0 || employee.in_vacation ? "readonly" : "") + '  class="form-control form-control-sm ' + (employee.in_vacation ? "" : "time-in") + '">');
                let time_out = $('<td class="align-middle"></td>').html(
                '<input type="time" name="attendance[' + employee.employee_id + '][time_out]" min="' + starting_time + '" max="' + leaving_time +
                '" value="' + employee.time_out + '" ' + (employee.present == 0 || employee.in_vacation ? "readonly" : "") + ' class="form-control form-control-sm ' + (employee.in_vacation ? "" : "time-out") + '">');
                let note = $('<td class="align-middle"></td>').html(
                    '<textarea name="attendance[' + employee.employee_id + '][note]" ' + (employee.present == 0 || employee.in_vacation ? "readonly" : "") + ' cols="40" rows="2" class="form-control ' + (employee.in_vacation ? "" : "note") + '">' + employee.note + '</textarea>'
                );
                let badges = $('<td class="align-middle"></td>').html(
                    (employee.in_vacation ? '<span class="badge badge-info mr-1">in-vacation</span>' :
                    (employee.has_attendance && employee.present ? '<span class="badge badge-success mr-1">Attend</span>' :
                    (employee.has_attendance && !employee.present ? '<span class="badge badge-danger mr-1">absent</span>' :
                    (!employee.has_attendance && !employee.present ? '<span class="badge badge-danger mr-1">no-attendance</span>' : ''
                    )
                    )
                    )
                    )

                );
                let tr = $('<tr></tr>').append(number, present, name, time_in, time_out, note, badges);
                tr.attr('id', employee.id);
                $('#attendance_table tbody').append(tr);
                if (employee.in_vacation) {
                    $('#attendance_table tbody').append(tr);
                } else {
                    $('#attendance_table tbody').prepend(tr);
                    $('.row-number').each(function() {
                        $(this).text(parseInt($(this).text()) + 1);
                    });
                    tr.find('.row-number').text(1);
                }
            });
        }

        $('body').on('change', '.check-all-box', function() {
            let time_in_td = $('.time-in');
            let time_out_td = $('.time-out');
            let note_td = $('.note');
            if ($(this).prop('checked')) {
                $('.check-box').prop('checked', true);
                time_in_td.prop('readonly', false);
                time_out_td.prop('readonly', false);
                note_td.prop('readonly', false);
                time_in_td.val(starting_time);
                time_out_td.val(leaving_time);
            } else {
                $('.check-box').prop('checked', false);
                time_in_td.prop('readonly', true);
                time_out_td.prop('readonly', true);
                note_td.prop('readonly', true);
                time_in_td.val('');
                time_out_td.val('');
                note_td.val('');
            }
        });

        $('body').on('change', '.check-box', function() {
            let row = $(this).closest("tr");
            let time_in_td = row.find('.time-in');
            let time_out_td = row.find('.time-out');
            let note_td = row.find('.note');
            if (this.checked) {
                time_in_td.prop('readonly', false);
                time_out_td.prop('readonly', false);
                note_td.prop('readonly', false);
                time_in_td.val(starting_time);
                time_out_td.val(leaving_time);
                $('.check-all-box').prop('checked', $('#attendance_table .check-box:checked').length === $('#attendance_table .check-box').length);
            } else {
                time_in_td.prop('readonly', true);
                time_out_td.prop('readonly', true);
                note_td.prop('readonly', true);
                time_in_td.val('');
                time_out_td.val('');
                note_td.val('');
                $('.check-all-box').prop('checked', false);
            }
        });

        $('body').on('blur', '.time-in', function() {
            if ($(this).is(":invalid")) {
                $(this).val(starting_time);
            }
        });

        $('body').on('blur', '.time-out', function() {
            if ($(this).is(":invalid")) {
                $(this).val(leaving_time);
            }
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/_custom/confirmDeletion.js') }}" defer></script>
@endpush

@push('style')
  <style>
    input[readonly] {
        background-color: #e9ecef;
        opacity: 1;
    }
    label {
        margin-bottom: 0;!important
    }
    table {
      table-layout: fixed;
      width: 100%;
      max-width: 100%;
    }

    .custom-checkbox {
      position: relative;
      line-height: 1;
    }

    .custom-checkbox input {
      visibility: hidden;
      margin-right: 8px;
    }

    .custom-label:before,
    .custom-label:after {
      width: 16px;
      height: 16px;
      content: "";
      border: 1px solid;
      display: inline-block;
      position: absolute;
      left: 0;
      top: 0px;
      border: #adb5bd solid 1px;
      transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
      border-radius: .25rem;
    }

    .custom-checkbox input:checked+.custom-label:before {
      border-color: #007bff;
      background-color: #007bff;
    }

    .custom-checkbox input:checked+.custom-label:after {
      width: 4px;
      border: 2px solid #ffffff;
      height: 8px;
      border-top: none;
      border-left: none;
      transform: rotate(40deg);
      left: 6px;
      top: 3px;
    }

  </style>
@endpush

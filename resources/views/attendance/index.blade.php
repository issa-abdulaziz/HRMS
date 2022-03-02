@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Attendence</h3>
    <div class="d-flex justify-content-between align-items-center">
      <h6 id="attendance-info" class="mr-3" style="display: none;color: red;">No Attendance taken</h6>
      <button id="btn-cancel" class="btn btn-outline-danger mr-2">cancel</button>
      <button id="btn-save" class="btn btn-primary">Save</button>
    </div>
  </div>
  <div class="form-group row my-4">
    <label for="date" class="col-sm-2 col-form-label">Date:</label>
    <div class="col-sm-4">
      <input type="date" class="form-control" id="date" value="{{ $date ?? date('Y-m-d') }}">
    </div>
    <label for="minutes" class="col-sm-2 col-form-label">Shift:</label>
    <div class="col-sm-4">
      <select id="shift" class="custom-select" name="shift">
        @foreach ($shifts as $shift)
          <option value="{{ $shift->id }}" {{ old('shift') == $shift->id ? 'selected' : '' }}>{{ $shift->title }}
          </option>
        @endforeach
      </select>
    </div>
  </div>
  @if (count($employees) > 0)
    <table class="table table-stripped">
      <thead class="thead-light">
        <tr>
          <th style="width: 5%;">#</th>
          <th style="width: 7%;">Present</th>
          <th style="width: 23%;">Employee</th>
          <th style="width: 15%;">Time-In</th>
          <th style="width: 15%;">Time-Out</th>
          <th style="width: 35%">Note</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

    <div id="next-to-table" class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center pl-3">
        <label class="custom-checkbox m-0 mt-1"><input type="checkbox" class="check-all-box"><span
            class="custom-label"></span></label>
        <p class="m-0">Check All</p>
      </div>
    </div>

    <div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="noteModalLabel">Note</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="note" class="col-form-label">Note</label>
              <textarea class="form-control" id="note" cols="30" rows="5"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="btn-save-note">Save</button>
          </div>
        </div>
      </div>
    </div>
    @include('inc.confirmDeletion', array('title'=>'attendance'))
  @else
    <p>No Employee Added Yet</p>
  @endif
@endsection

@push('script')
  <script>
    $('#noteModal').on('show.bs.modal', function(event) {
      let td_note = $(event.relatedTarget);
      let tr = td_note.parents("tr").first();
      let full_name = tr.find('.full-name').text();
      $('#noteModalLabel').text(full_name);
      let note = td_note.text() === '...' ? '' : td_note.text();
      var modal = $(this);
      modal.find('.modal-body textarea').val(note);
      $('#btn-save-note').click(function() {
        let text = $('.modal-body textarea').val();
        $(event.relatedTarget).text(text === '' ? '...' : text);
        $('#noteModal').modal('hide');
      });
    });

    let starting_time, leaving_time;

    checkAttendance();

    $('#date').change(function() {
      checkAttendance();
    });

    $('#shift').change(function() {
      checkAttendance();
    });

    $('#btn-cancel').click(function() {
      checkAttendance();
    });

    function addDeleteButton() {
      removeDeleteButton();
      let date = $('#date').val();
      let btn_delete = $(
        '<button type="button" id="btn-delete" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeletionModal" data-id="' +
        date + '" data-date="' + date + '"></button>'
      ).text('Delete');
      $('#next-to-table').append(btn_delete);
    }

    function removeDeleteButton() {
      $('#btn-delete').remove();
    }

    function checkAttendance() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "POST",
        url: "/attendance/check",
        data: {
          date: $('#date').val(),
          shift_id: $('#shift').val()
        },
        dataType: 'json',
        success: function(response) {
          starting_time = response.shift.starting_time;
          leaving_time = response.shift.leaving_time;
          displayEmployee(response.employees);
          if (response.employeesInVacation.length > 0)
            displayEmployeeInVacation(response.employeesInVacation);
          if (response.attendance.length > 0) {
            $('#attendance-info').css('display', 'none');
            fillData(response.attendance);
          } else {
            removeDeleteButton();
            $('#attendance-info').css('display', 'block');
            $('.check-all-box').prop('checked', false);
          }
        },
        error: function(data) {
          console.log('Error:', data);
        }
      });
    }

    function displayEmployee(employees) {
      $('tbody').empty();
      employees.forEach((employee, index) => {
        let number = $('<td></td>').html(index + 1);
        let present = $('<td></td>').html(
          '<label class="custom-checkbox"><input type="checkbox" class="check-box present"><span class="custom-label"></span></label>'
        );
        let name = $('<td class="full-name"></td>').html('<a href="/employee/' + employee.id + '">' + employee
          .full_name + '</a>');
        let time_in = $('<td></td>').html(
          '<input type="time" min="' + starting_time + '" max="' + leaving_time +
          '" class="form-control form-control-sm time-in time_in" disabled>');
        let time_out = $('<td></td>').html(
          '<input type="time" min="' + starting_time + '" max="' + leaving_time +
          '" class="form-control form-control-sm time-out time_out" disabled>');
        let note = $(
          '<td style="text-overflow: ellipsis;" class="note text-nowrap overflow-hidden px-3" data-toggle="modal" data-target="#noteModal" title="">...</td>'
        );
        let tr = $('<tr></tr>').append(number, present, name, time_in, time_out, note);
        tr.attr('id', employee.id);
        $('tbody').append(tr);
      });
    }

    function displayEmployeeInVacation(employees) {
      let td = $('<td colspan="6" class="text-center"></td>').html("In Vacation");
      $('tbody').append($('<tr></tr>').append(td));
      employees.forEach((employee) => {
        let currentRow = $('tbody tr').length;
        let number = $('<td></td>').html(currentRow);
        let present = $('<td></td>').html(
          '<label class="custom-checkbox"><input type="checkbox" class="present" disabled><span class="custom-label"></span></label>'
        );
        let name = $('<td class="full-name"></td>').html('<a href="/employee/' + employee.id + '">' + employee
          .full_name + '</a>');
        let time_in = $('<td></td>').html(
          '<input type="time" min="' + starting_time + '" max="' + leaving_time +
          '" class="time_in form-control form-control-sm" disabled>');
        let time_out = $('<td></td>').html(
          '<input type="time" min="' + starting_time + '" max="' + leaving_time +
          '" class="time_out form-control form-control-sm" disabled>');
        let note = $(
          '<td style="text-overflow: ellipsis;" class="note text-nowrap overflow-hidden px-3" data-toggle="modal" data-target="#noteModal" title="">...</td>'
        );
        let tr = $('<tr></tr>').append(number, present, name, time_in, time_out, note);
        tr.attr('id', employee.id);
        $('tbody').append(tr);
      });
    }

    function fillData(attendance) {
      addDeleteButton();
      let presentEmployees = 0;
      attendance.forEach(entry => {
        let row = $('#' + entry.employee_id);
        row.find('.check-box').prop("checked", entry.present);
        row.find('.note').text(entry.note);
        row.find('.note').attr('title', entry.note);
        if (entry.present) {
          presentEmployees++;
          row.find('.time-in').val(entry.time_in);
          row.find('.time-out').val(entry.time_out);
          row.find('.time-in').prop('disabled', false);
          row.find('.time-out').prop('disabled', false);
        } else {
          row.find('.time-in').prop('disabled', true);
          row.find('.time-out').prop('disabled', true);
        }
      });
      $('.check-all-box').prop('checked', presentEmployees === $('.check-box').length);
    }

    $('body').on('change', '.check-box', function() {
      let row = $(this).parents("tr").first();
      if (this.checked) {
        row.find('.time-in').prop('disabled', false);
        row.find('.time-out').prop('disabled', false);
        row.find('.time-in').val(starting_time);
        row.find('.time-out').val(leaving_time);
        $('.check-all-box').prop('checked', $('.check-box:checked').length === $('.check-box').length);
      } else {
        row.find('.time-in').prop('disabled', true);
        row.find('.time-out').prop('disabled', true);
        row.find('.time-in').val('');
        row.find('.time-out').val('');
        $('.check-all-box').prop('checked', false);
      }
    });

    $('body').on('change', '.check-all-box', function() {
      if ($(this).prop('checked')) {
        $('.check-box').prop('checked', true);
        $('.time-in').prop('disabled', false);
        $('.time-out').prop('disabled', false);
        $('.time-in').val(starting_time);
        $('.time-out').val(leaving_time);
      } else {
        $('.check-box').prop('checked', false);
        $('.time-in').prop('disabled', true);
        $('.time-out').prop('disabled', true);
        $('.time-in').val('');
        $('.time-out').val('');
      }
    });

    $('#btn-save').click(function() {
      $('table tbody tr').get().forEach(function(row, index, array) {
        let employee_id = $(row).attr('id');
        let present = $(row).find('.present').prop('checked');
        let time_in = $(row).find('.time_in').val();
        let time_out = $(row).find('.time_out').val();
        let note = $(row).find('.note').text();
        let date = $('#date').val();
        if (employee_id) {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.ajax({
            type: "POST",
            url: "/attendance/store",
            dataType: 'json',
            data: {
              employee_id,
              present,
              time_in,
              time_out,
              note,
              date
            },
            success: function(response) {
              if (index === array.length - 1) {
                $('#attendance-info').css('display', 'none');
                addDeleteButton();
                $('main').prepend('<div class="alert alert-success m-3">Saved Successfully</div>');
                setTimeout(function() {
                  $('.alert-success').alert('close');
                }, 3000);
              }
            },
            error: function(data) {
              console.log('Error:', data);
            }
          });
        }
      });
    });

    $('body').on('click', '#btn-delete', function() {
      let date = $('#date').val();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "DELETE",
        url: "/attendance/destroy",
        dataType: 'json',
        data: {
          date
        },
        success: function(response) {
          checkAttendance();
        },
        error: function(data) {
          console.log('Error:', data);
        }
      });
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
  <script type="text/javascript" src="{{ asset('js/_custom/confirmDeletion.js') }}"></script>
@endpush

@push('style')
  <style>
    table {
      table-layout: fixed;
      width: 100%;
      max-width: 100%;
    }

    .custom-checkbox {
      position: relative;
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

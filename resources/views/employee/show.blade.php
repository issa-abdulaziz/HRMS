@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>{{ $employee->full_name }}</h3>
    <input type="hidden" id="employee_id" value="{{ $employee->id }}">
    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
  </div>
  <div class="container-fluid mb-5">
    <div class="row mb-4">
      <div class="col-lg-3 col-md-6">
        <div class="card" style="background-color: rgba(255, 99, 132, 0.9)">
          <div class="card-body">
            <div class="d-flex no-block">
              <div class="me-3 align-self-center mr-3">
                <i class="fa fa-clock fa-2x text-light" aria-hidden="true"></i>
              </div>
              <div class="align-self-center">
                <h6 class="text-light mt-2 mb-0">In Time Percnetage</h6>
                <h2 class="text-white mt-1">{{ $inTimePercentage }}%</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card" style="background-color: rgba(54, 162, 235, 0.9)">
          <div class="card-body">
            <div class="d-flex no-block">
              <div class="me-3 align-self-center mr-3">
                <i class="fa fa-bed fa-2x text-light" aria-hidden="true"></i>
              </div>
              <div class="align-self-center">
                <h6 class="text-light mt-2 mb-0">Absent Days</h6>
                <h2 class="text-white mt-1">{{ $absentDay }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card" style="background-color: rgba(255, 206, 86, 0.9)">
          <div class="card-body">
            <div class="d-flex no-block">
              <div class="me-3 align-self-center mr-3">
                <i class="fa fa-bell fa-2x text-light" aria-hidden="true"></i>
              </div>
              <div class="align-self-center">
                <h6 class="text-light mt-2 mb-0">Total Leeway Hours</h6>
                <h2 class="text-white mt-1">{{ $leewayTime }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card" style="background-color: rgba(153, 102, 255, 0.9)">
          <div class="card-body">
            <div class="d-flex no-block">
              <div class="me-3 align-self-center mr-3">
                <i class="fa fa-clock fa-2x text-light" style="color: #38c172;" aria-hidden="true"></i>
              </div>
              <div class="align-self-center">
                <h6 class="text-light mt-2 mb-0">Vacation</h6>
                <h2 class="text-white mt-1">{{ $vacationDays }}</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-lg-8">
        <canvas id="myChart"></canvas>
      </div>
      {{-- <div class="col-lg-9 d-flex align-items-stretch">
        <div class="card w-100">
          <h3 class="card-title mb-1 border-left border-bottom border-primary p-3"
            style="border-left-width: 7px !important;">Sales Overview</h3>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <div class="card border-0">
                  <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                      <div>
                        <h6 class="text-muted text-center mt-2 mb-0">Overtime</h6>
                        <h3 class="text-dark text-center mt-1">{{ $overtimeAmount }} {{ $currency }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-12">
                <div class="card border-0">
                  <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                      <div>
                        <h6 class="text-muted text-center mt-2 mb-0">Leeway</h6>
                        <h3 class="text-dark text-center mt-1">{{ $leewayDiscount }} {{ $currency }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <div class="card border-0">
                  <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                      <div>
                        <h6 class="text-muted text-center mt-2 mb-0">Absent Day</h6>
                        <h3 class="text-dark text-center mt-1">
                          {{ $absentDayDiscountAmount }} {{ $currency }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-12">
                <div class="card border-0">
                  <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                      <div>
                        <h6 class="text-muted text-center mt-2 mb-0">Advanced Payment</h6>
                        <h3 class="text-dark text-center mt-1">{{ $advancedPaymentAmount }} {{ $currency }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="card border-0">
                  <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                      <div>
                        <h6 class="text-muted text-center mt-2 mb-0">Total</h6>
                        <h3 class="text-dark text-center mt-1">{{ $total }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
      <!-- -------------------------------------------------------------- -->
      <!-- General Information -->
      <!-- -------------------------------------------------------------- -->
      <div class="col-lg-4 d-flex align-items-stretch">
        <div class="card w-100">
          <h4 class="card-title mb-1 border-left border-bottom border-primary p-3"
            style="border-left-width: 7px !important;color:rgb(102, 102, 102);font-weight:bold">General Information</h3>
            <div class="card-body">
              <table class="table vm fs-3">
                <tr>
                  <td>Position</td>
                  <td class="text-end font-weight-medium">{{ $employee->position }}</td>
                </tr>
                <tr>
                  <td>Salary</td>
                  <td class="text-end font-weight-medium">{{ $employee->salary }} {{ session('setting')->currency }}
                  </td>
                </tr>
                <tr>
                  <td>Work Shift</td>
                  <td class="text-end font-weight-medium">{{ $employee->shift?->title }}</td>
                </tr>
                <tr>
                  <td>Hired At</td>
                  <td class="text-end font-weight-medium">{{ $employee->hired_at }}</td>
                </tr>
                <tr>
                  <td>Vacation Start At</td>
                  <td class="text-end font-weight-medium">
                    {{ $employee->vacation_start_count_at ? $employee->vacation_start_count_at : 'Not Started Yet' }}
                  </td>
                </tr>
                <tr>
                  <td class="b-0">Phone Number</td>
                  <td class="text-end font-weight-medium b-0">{{ $employee->phone_number }}</td>
                </tr>
                <tr>
                  <td>Date of Birth</td>
                  <td class="text-end font-weight-medium">{{ $employee->date_of_birth }}</td>
                </tr>
                <tr>
                  <td>City</td>
                  <td class="text-end font-weight-medium">{{ $employee->city }}</td>
                </tr>
              </table>
            </div>
        </div>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="attendance-tab" data-toggle="tab" href="#attendance" role="tab"
              aria-controls="attendance" aria-selected="false">Attendance</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="overtime-tab" data-toggle="tab" href="#overtime" role="tab"
              aria-controls="overtime" aria-selected="true">Overtime</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="advanced-payment-tab" data-toggle="tab" href="#advanced-payment" role="tab"
              aria-controls="advanced-payment" aria-selected="false">Advanced Payment</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="vacation-tab" data-toggle="tab" href="#vacation" role="tab"
              aria-controls="vacation" aria-selected="false">Vacation</a>
          </li>
        </ul>
        <div class="tab-content mt-3" id="myTabContent">
          <div class="tab-pane fade" id="overtime" role="tabpanel" aria-labelledby="overtime-tab">
            @if (count($employee->overtimes) > 0)
              <table class="table hover stripe row-border" id="table_id" style="width:100%">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Rate</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($employee->overtimes->take(10) as $overtime)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td><a href="/overtime/{{ $overtime->id }}">{{ $overtime->employee->full_name }}</a></td>
                      <td>{{ $overtime->date }}</td>
                      <td>{{ floor($overtime->time / 60) }}:{{ $overtime->time % 60 }}</td>
                      <td>x{{ $overtime->rate }}</td>
                      <td>{{ $overtime->amount }} {{ session('setting')->currency }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <p>{{ $employee->full_name }} does not has overtime this month yet</p>
            @endif
          </div>
          <div class="tab-pane fade" id="vacation" role="tabpanel" aria-labelledby="vacation-tab">
            @if (count($employee->vacations) > 0)
              <table class="table nowrap" style="width:100%">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Note</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($employee->vacations->take(10) as $vacation)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td><a href="/employee/{{ $vacation->employee->id }}">{{ $vacation->employee->full_name }}</a>
                      </td>
                      <td>{{ $vacation->date_from }}</td>
                      <td>{{ $vacation->date_to }}</td>
                      <td>{{ $vacation->note }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @elseif ($employee->can_take_vacation)
              <p>{{ $employee->full_name }} does not take vacation this month yet</p>
            @else
              <p>{{ $employee->full_name }} can not take vacation</p>
            @endif
          </div>
          <div class="tab-pane fade" id="advanced-payment" role="tabpanel" aria-labelledby="advanced-payment-tab">
            @if (count($employee->advancedPayments) > 0)
              <table class="table hover stripe row-border" id="table_id" style="width:100%">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Date</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($employee->advancedPayments->take(10) as $advancedPayment)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td><a
                          href="/advanced-payment/{{ $advancedPayment->id }}">{{ $advancedPayment->employee->full_name }}</a>
                      </td>
                      <td>{{ $advancedPayment->date }}</td>
                      <td>{{ $advancedPayment->amount }} {{ session('setting')->currency }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <p>{{ $employee->full_name }} does not take Advanced Payment this month Yet</p>
            @endif
          </div>
          <div class="tab-pane fade show active" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
            @if (count($employee->attendances) > 0)
              <table class="table table-stripped">
                <thead class="thead-light">
                  <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 10%;">Date</th>
                    <th style="width: 10%;">Present</th>
                    <th style="width: 15%;">Time-In</th>
                    <th style="width: 15%;">Time-Out</th>
                    <th style="width: 45%">Note</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($employee->attendances->take(30) as $attendance)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>{{ $attendance->date }}</td>
                      <td>
                        @if ($attendance->present)
                          <i class="fa fa-check-square" style="font-size: 18px;color:rgb(0,128,255)"></i>
                        @else
                          <i class="fa fa-square" style="font-size: 18px;color:rgb(0,128,255)"></i>
                        @endif
                      </td>
                      <td>{{ $attendance->time_in ? date('h:i a', strtotime($attendance->time_in)) : '--:--' }}</td>
                      <td>{{ $attendance->time_out ? date('h:i a', strtotime($attendance->time_out)) : '--:--' }}</td>
                      <td style="text-overflow: ellipsis;" class="text-nowrap overflow-hidden px-3"
                        title="{{ $attendance->note }}">{{ $attendance->note }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <p>No Attendence Added Yet</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@push('script')
  <script type="text/javascript" src="{{ asset('js/_custom/Chart.bundle.js') }}"></script>

  <script type="text/javascript">
    $(function() {
      let monthsLabel
      let overtimeData = [];
      let absenceData = [];
      let leewayData = [];
      let advancedPaymentData = [];
      let overallData = [];

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "GET",
        dataType: 'json',
        url: "{{ route('employee.getData', ['%id%']) }}".replace('%id%', $('#employee_id').val()),
        success: function(response) {
          monthsLabel = response.monthsLabel;
          response.data.forEach(element => {
            overtimeData.push(element['overtimeTotal']);
            absenceData.push(element['absenceTotal']);
            leewayData.push(element['leewayTotal']);
            advancedPaymentData.push(element['advancedPaymentTotal']);
            overallData.push(element['overall']);
          });
          displayData();
        },
        error: function(data) {
          console.log('Error:', data);
        }
      });

      function displayData() {
        const config = {
          type: 'line',
          data: {
            labels: monthsLabel,
            datasets: [{
              label: 'Overall',
              backgroundColor: 'rgba(153, 102, 255, 0.9)',
              borderColor: 'rgba(153, 102, 255, 0.9)',
              fill: false,
              data: overallData,
            }, {
              label: 'Overtime',
              backgroundColor: 'rgba(255, 99, 132, 0.9)',
              borderColor: 'rgba(255, 99, 132, 0.9)',
              fill: false,
              data: overtimeData,
            }, {
              label: 'Attendance',
              backgroundColor: 'rgba(54, 162, 235, 0.9)',
              borderColor: 'rgba(54, 162, 235, 0.9)',
              fill: false,
              data: absenceData,
            }, {
              label: 'Leeway',
              backgroundColor: 'rgba(255, 206, 86, 0.9)',
              borderColor: 'rgba(255, 206, 86, 0.9)',
              fill: false,
              data: leewayData,
            }, {
              label: 'Advanced Payment',
              backgroundColor: 'rgba(57, 192, 192, 0.9)',
              borderColor: 'rgba(57, 192, 192, 0.9)',
              fill: false,
              data: advancedPaymentData,
            }]
          },
          options: {
            title: {
              display: true,
              text: 'Last 12 Month Data',
              fontSize: 25,
            },
            legend: {
              position: 'right'
            }
          }
        };

        const myChart = new Chart(
          $('#myChart'),
          config
        );
      }

    });
  </script>
@endpush

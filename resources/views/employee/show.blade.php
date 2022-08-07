@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $employee->full_name }}</h1>
                    <input type="hidden" id="employee_id" value="{{ $employee->id }}">
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $inTimePercentage }}</h3>

                            <p>In Time Percnetage</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-clock" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $absentDay }}</h3>

                            <p>Absent Days</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-bed"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $leewayTime }}</h3>

                            <p>Total Leeway Hours</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-bell" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $vacationDays }}</h3>

                            <p>Vacation</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-clock" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <div class="row">

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Last 12 Month Data</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- -------------------------------------------------------------- -->
                <!-- General Information -->
                <!-- -------------------------------------------------------------- -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                General Information
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>Work Shift</td>
                                        <td class="text-end font-weight-medium">{{ $employee->shift?->title }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Position</td>
                                        <td class="text-end font-weight-medium">{{ $employee->position }}</td>
                                    </tr>
                                    <tr>
                                        <td>Salary</td>
                                        <td class="text-end font-weight-medium">{{ $employee->salary }}
                                            {{ session('setting')->currency }}
                                        </td>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Relative Data</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#attendance" data-toggle="tab">Attendance</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#overtime" data-toggle="tab">Overtime</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#advanced-payment" data-toggle="tab">Advanced Payment</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#vacation" data-toggle="tab">Vacation</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">

                            <div class="tab-content">
                                <div class="tab-pane fade" id="overtime">
                                    @if (count($employee->overtimes) > 0)
                                        <table class="table table-striped table-hover nowrap" style="width:100%">
                                            <thead class="thead-dark">
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
                                                        <td><a
                                                                href="/overtime/{{ $overtime->id }}">{{ $overtime->employee->full_name }}</a>
                                                        </td>
                                                        <td>{{ $overtime->date }}</td>
                                                        <td>{{ floor($overtime->time / 60) }}:{{ $overtime->time % 60 }}
                                                        </td>
                                                        <td>x{{ $overtime->rate }}</td>
                                                        <td>{{ $overtime->amount }} {{ session('setting')->currency }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>{{ $employee->full_name }} does not has overtime this month yet</p>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="vacation">
                                    @if (count($employee->vacations) > 0)
                                        <table class="table table-striped table-hover nowrap" style="width:100%">
                                            <thead class="thead-dark">
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
                                                        <td><a
                                                                href="/employee/{{ $vacation->employee->id }}">{{ $vacation->employee->full_name }}</a>
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
                                <div class="tab-pane fade" id="advanced-payment">
                                    @if (count($employee->advancedPayments) > 0)
                                        <table class="table table-striped table-hover nowrap" style="width:100%">
                                            <thead class="thead-dark">
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
                                                        <td>{{ $advancedPayment->amount }}
                                                            {{ session('setting')->currency }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>{{ $employee->full_name }} does not take Advanced Payment this month Yet</p>
                                    @endif
                                </div>
                                <div class="tab-pane fade show active" id="attendance">
                                    @if (count($employee->attendances) > 0)
                                        <table class="table table-striped table-hover nowrap" style="width:100%">
                                            <thead class="thead-dark">
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
                                                                <i class="fa fa-check-square"
                                                                    style="font-size: 18px;color:rgb(0,128,255)"></i>
                                                            @else
                                                                <i class="fa fa-square"
                                                                    style="font-size: 18px;color:rgb(0,128,255)"></i>
                                                            @endif
                                                        </td>
                                                        <td>{{ $attendance->time_in ? date('h:i a', strtotime($attendance->time_in)) : '--:--' }}
                                                        </td>
                                                        <td>{{ $attendance->time_out ? date('h:i a', strtotime($attendance->time_out)) : '--:--' }}
                                                        </td>
                                                        <td style="text-overflow: ellipsis;"
                                                            class="text-nowrap overflow-hidden px-3"
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
            </div>

        </div>
    </section>
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
                            display: false,
                            text: 'Last 12 Month Data',
                            fontSize: 25,
                        },
                        legend: {
                            position: 'top'
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

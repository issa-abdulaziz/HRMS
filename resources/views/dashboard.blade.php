@extends('layouts.app')

@section('content')
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
                <h6 class="text-light mt-2 mb-0">Overtime</h6>
                <h2 class="text-white mt-1">{{ $overtimeTotal }} <small>{{ session('setting')->currency }}</small>
                </h2>
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
                <h6 class="text-light mt-2 mb-0">Attendance</h6>
                <h2 class="text-white mt-1">{{ $absenceTotal }} <small>{{ session('setting')->currency }}</small></h2>
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
                <h6 class="text-light mt-2 mb-0">Leeway</h6>
                <h2 class="text-white mt-1">{{ $leewayTotal }} <small>{{ session('setting')->currency }}</small></h2>
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
                <h6 class="text-light mt-2 mb-0">Overall</h6>
                <h2 class="text-white mt-1">{{ $overall }} <small>{{ session('setting')->currency }}</small></h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-lg-9">
        <canvas id="myChart"></canvas>
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
      let overallData = [];

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "GET",
        url: "/dashboard/getData",
        success: function(response) {
          monthsLabel = response.monthsLabel;
          response.data.forEach(element => {
            overtimeData.push(element['overtimeTotal']);
            absenceData.push(element['absenceTotal']);
            leewayData.push(element['leewayTotal']);
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
            }]
          },
          options: {
            title: {
              display: true,
              text: 'Last 12 Month Report',
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

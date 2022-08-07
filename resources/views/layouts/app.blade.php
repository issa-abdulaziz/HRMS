<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    @stack('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <!-- ./wrapper -->
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('images/goal_127px.png') }}" alt="Rigth Click" height="60"
                width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-lg fa-bars"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                <img src="{{ asset('images/goal_127px.png') }}" alt="HRMS"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">HRMS</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.index') }}"
                                class="nav-link {{ request()->is('employee*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Employee
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendance.index') }}"
                                class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-check"></i>
                                <p>
                                    Attendance
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('overtime.index') }}"
                                class="nav-link {{ request()->is('overtime*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clock"></i>
                                <p>
                                    Overtime
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vacation.index') }}"
                                class="nav-link {{ request()->is('vacation*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-globe-europe"></i>
                                <p>
                                    Vacation
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('advanced-payment.index') }}"
                                class="nav-link {{ request()->is('advanced-payment*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-donate"></i>
                                <p>
                                    Advanced Payment
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('salary.index') }}"
                                class="nav-link {{ request()->is('salary*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p>
                                    Salary
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('shift.index') }}"
                                class="nav-link {{ request()->is('shift*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Work Shift
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="nav-icon fas fa-door-open"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @include('inc.messages')
            @yield('content')
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer d-flex align-items-center justify-content-between">
            <div>Powered By <strong>Issa Abdulaziz</strong></div>
            <nav>
                <ul class="nav">
                    <li class="nav-item ml-2">
                        <a class="nav-link text-dark" target="blank"
                            href="https://github.com/issa-abdulaziz/right-clicks">
                            <i class="fab fa-xl fa-github"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-red" target="blank"
                            href="https://stackoverflow.com/users/15409405/issa-abdulaziz">
                            <i class="fab fa-xl fa-stack-overflow"></i>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a class="nav-link text-primary" target="blank"
                            href="https://www.linkedin.com/in/issa-abdulaziz/">
                            <i class="fab fa-xl fa-linkedin"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </footer>

        <!-- /.control-sidebar -->
    </div>

    <script>
        $(document).ready(function() {
            var table = $('.datatable').DataTable({
                "responsive": true,
                "columnDefs": [{
                    "targets": [-1],
                    "orderable": false,
                    "responsivePriority": 1,
                }, ],
                "lengthMenu": [
                    [10, 25, -1],
                    [10, 25, "All"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'copyHtml5',
                    'excel',
                    'csvHtml5',
                    'pdfHtml5',
                    'print',
                ],
            });
            table.columns().every(function(colID) {
                let header = $(table.column(colID).header());
                let placeholderDataAttr = header.data('footer-filter-placeholder');
                let placeholder = placeholderDataAttr ? placeholderDataAttr : 'Search For ' + header.text();
                if (header.data('hide-footer-filter'))
                    return;
                var mySelectList = $("<input class='form-control' placeholder='" + placeholder + "' />")
                    .appendTo(table.column(colID).footer())
                    .on("keyup", function() {
                        table.column(colID).search($(this).val()).draw();
                    });
            });
        });
    </script>

    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.js') }}"></script>

    <script>
        // This code is for setting timer for the messages that appear when needed
        setTimeout(function() {
            $('#alert-success').alert('close');
        }, 4000);
    </script>
    @stack('script')
</body>

</html>

@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Employees</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('employee.create') }}" class="btn btn-success">Add New Employee</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (count($employees) > 0)
                                <table class="datatable table-striped table-hover nowrap table" width="100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th data-hide-footer-filter="true">#</th>
                                            <th>Full Name</th>
                                            <th>Phone Number</th>
                                            <th>Postion</th>
                                            <th>Salary</th>
                                            <th>Hired At</th>
                                            <th>City</th>
                                            <th>Vacation Days</th>
                                            <th>Vacation Start Count At</th>
                                            <th>Active</th>
                                            <th data-hide-footer-filter="true" class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $employee)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('employee.show', $employee->id) }}">{{ $employee->full_name }}</a>
                                                </td>
                                                <td>{{ $employee->phone_number }}</td>
                                                <td>{{ $employee->position }}</td>
                                                <td>{{ $employee->salary }} {{ session('setting')->currency }}</td>
                                                <td>{{ $employee->hired_at }}</td>
                                                <td>{{ $employee->city }}</td>
                                                <td>{{ $employee->vacation_days }}</td>
                                                <td>{{ $employee->vacation_start_count_at }}</td>
                                                <td>
                                                    @if ($employee->active)
                                                        <span class="badge badge-pill badge-success">active</span>
                                                    @else
                                                        <span class="badge badge-pill badge-danger">in-active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('employee.edit', $employee->id) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#confirmDeletionModal" data-id="{{ $employee->id }}"
                                                        data-name="{{ $employee->full_name }}"
                                                        data-date="{{ $employee->hired_at }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                @include('inc.confirmDeletion', [
                                    'title' => 'employee',
                                ])
                            @else
                                <p>No Employee Added Yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@push('script')
    <script type="text/javascript" src="{{ asset('js/_custom/confirmDeletion.js') }}" defer></script>
@endpush

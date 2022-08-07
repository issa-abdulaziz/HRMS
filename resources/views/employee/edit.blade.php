@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit employee</h1>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('employee.update', $employee->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" value="{{ $employee->full_name }}" id="full_name" name="full_name"
                                        class="form-control" required minlength="3" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" value="{{ $employee->phone_number }}" id="phone_number"
                                        name="phone_number" class="form-control" required minlength="8" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" value="{{ $employee->date_of_birth }}" id="date_of_birth"
                                        name="date_of_birth" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" value="{{ $employee->city }}" id="city" name="city"
                                        class="form-control" required minlength="3" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" value="{{ $employee->position }}" id="position" name="position"
                                        class="form-control" required minlength="3" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label for="salary">Salary (in {{ session('setting')->currency }})</label>
                                    <input type="number" value="{{ $employee->salary }}" id="salary" name="salary"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="hired_at">Hired at</label>
                                    <input type="date" value="{{ $employee->hired_at }}" id="hired_at" name="hired_at"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="vacation_start_count_at">Start counting for vacation at</label>
                                    <input type="month" value="{{ substr($employee->vacation_start_count_at, 0, -3) }}"
                                        id="vacation_start_count_at" name="vacation_start_count_at" class="form-control">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input id="active" class="custom-control-input" type="checkbox" name="active"
                                            {{ $employee->active == true ? 'checked' : '' }}>
                                        <label for="active" class="custom-control-label">Active</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="shift_id">Work Shift</label>
                                    <select id="shift_id" class="custom-select" name="shift_id" required>
                                        <option disabled selected>select Shift</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}"
                                                {{ $employee->shift_id == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-success btn-block w-50 mx-auto" name="submit" id="submit" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $('body').on('change', '#hired_at', setMinDate);

        if ($('#hired_at').val()) {
            setMinDate();
        }

        function setMinDate() {
            let date = new Date($('#hired_at').val());
            $('#vacation_start_count_at').attr('min', date.getFullYear() + '-' + (date.getMonth() + 1));
            checkDateFormat();
        }

        function checkDateFormat() {
            let minDate = $('#vacation_start_count_at').attr('min');
            if (minDate.length == 6) {
                let newMinDate = minDate.substring(0, 5) + '0' + minDate.substring(5);
                $('#vacation_start_count_at').attr('min', newMinDate);
            }
        }
    </script>
@endpush

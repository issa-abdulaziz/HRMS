@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Advanced Payment</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('advanced-payment.create') }}" class="btn btn-success">Add New One</a>
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
                            <form action="{{ route('advanced-payment.index') }}" method="GET" id="form-filter">
                                @csrf
                                <input type="month" id="date" name="date" value="{{ $date }}"
                                    class="form-control my-3">
                            </form>

                            @if (count($advancedPayments) > 0)
                                <table class="datatable table-striped table-hover nowrap table" width="100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 4%;" data-hide-footer-filter="true">#</th>
                                            <th style="width: 26%;">Full Name</th>
                                            <th style="width: 15%;">Date</th>
                                            <th style="width: 15%;">Amount</th>
                                            <th style="width: 30%;">Note</th>
                                            <th style="width: 10%;" data-hide-footer-filter="true" class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($advancedPayments as $advancedPayment)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('employee.show', $advancedPayment->employee->id) }}">{{ $advancedPayment->employee->full_name }}</a>
                                                </td>
                                                <td>{{ $advancedPayment->date }}</td>
                                                <td>{{ $advancedPayment->amount }} {{ session('setting')->currency }}</td>
                                                <td title="{{ $advancedPayment->note }}">{{ $advancedPayment->note }}</td>
                                                <td class="text-right">
                                                    <a class="btn btn-success btn-sm"
                                                        href="{{ route('advanced-payment.edit', $advancedPayment->id) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#confirmDeletionModal"
                                                        data-id="{{ $advancedPayment->id }}"
                                                        data-name="{{ $advancedPayment->employee->full_name }}"
                                                        data-date="{{ $advancedPayment->date }}"
                                                        data-amount=" {{ $advancedPayment->amount }}"
                                                        data-currency="{{ session('setting')->currency }}">
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
                                        </tr>
                                    </tfoot>
                                </table>
                                @include('inc.confirmDeletion', [
                                    'title' => 'advanced-payment',
                                ])
                            @else
                                <p>No Advanced Payment Added Yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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

            $('#date').change(function() {
                $('#form-filter').submit();
            });

        });
    </script>
    <script type="text/javascript" src="{{ asset('js/_custom/confirmDeletion.js') }}"></script>
@endpush

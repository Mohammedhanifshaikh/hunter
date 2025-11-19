@extends('layouts.master')
@section('title', 'Company List - CRM')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between align-items-center">Company List
                <a href="{{ route('attach.plan') }}" class="btn btn-sm btn-primary">Attach Plan</a>
            </h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="companyTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Company Name</th>
                            <th>Pan No</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Subscription</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        $(document).ready(function() {
            var companyTable = $("#companyTable").DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                bFilter: true,
                lengthChange: true,
                pageLength: 10,
                order: [],
                ajax: "{{ route('fetch.company-list') }}",
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false,
                        searchable: false,
                        className: 'text-start',
                    },
                    {
                        data: "company_name",
                        name: "company_name"
                    },
                    {
                        data: "pan_no",
                        name: "pan_no"
                    },
                    {
                        data: "mobile_no",
                        name: "mobile_no"
                    },
                    {
                        data: "email",
                        name: "email"
                    },
                    {
                        data: "subscription",
                        name: "subscription",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "status",
                        name: "status"
                    },

                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on("click", ".company_delete_btn", function() {
            let companyId = $(this).data("id");
            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('delete.company') }}",
                    data: {
                        companyId: companyId,
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                            };
                            toastr.success(response.message);
                            $('#companyTable').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                            };
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.responseText);
                    },
                });
            }
        });

        $(document).on('click', '.change-status', function() {
            let companyId = $(this).data('id');
            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('change.company.status') }}",
                    data: {
                        companyId: companyId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true
                        };

                        if (response.status == true) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }

                        // Reload DataTable to reflect status change
                        $('#companyTable').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true
                        };
                        toastr.error("An error occurred while changing the status.");
                    }
                });
            }
        });
    </script>
@endpush

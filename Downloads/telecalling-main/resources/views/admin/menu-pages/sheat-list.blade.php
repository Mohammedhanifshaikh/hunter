@extends('layouts.master')
@section('title', 'Sheet List - CRM')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Sheet List</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="sheetTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Sheet Name</th>
                            <th>Company</th>
                            <th>Agent</th>
                            <th>Lead</th>
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
            var sheetTable = $("#sheetTable").DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                bFilter: true,
                lengthChange: true,
                pageLength: 10,
                order: [],
                ajax: "{{ route('fetch.sheat-list') }}",
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
                        data: "sheat_name",
                        name: "sheat_name"
                    },
                    {
                        data: "company",
                        name: "company"
                    },
                    {
                        data: "agent",
                        name: "agent"
                    },

                    {
                        data: "lead",
                        name: "lead",
                        className: 'text-start'
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

        $(document).on("click", ".sheat_delete_btn", function() {
            let sheatId = $(this).data("id");
            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('delete.sheat') }}",
                    data: {
                        sheatId: sheatId,
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                            };
                            toastr.success(response.message);
                            $('#sheetTable').DataTable().ajax.reload(null, false);
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

    </script>
@endpush

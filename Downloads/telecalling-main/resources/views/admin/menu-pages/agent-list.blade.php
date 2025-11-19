@extends('layouts.master')
@section('title', 'Agent List - CRM')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Agent List</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="agentTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Company Name</th>
                            <th>Agent Name</th>
                            <th>Contact</th>
                            <th>Email</th>
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
            var agentTable = $("#agentTable").DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                bFilter: true,
                lengthChange: true,
                pageLength: 10,
                order: [],
                ajax: "{{ route('fetch.agent-list') }}",
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
                        data: "company",
                        name: "company"
                    },
                    {
                        data: "agent_name",
                        name: "agent_name"
                    },
                    {
                        data: "phone",
                        name: "phone"
                    },
                    {
                        data: "email",
                        name: "email"
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

        $(document).on("click", ".agent_delete_btn", function() {
            let agentId = $(this).data("id");
            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('delete.agent') }}",
                    data: {
                        agentId: agentId,
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                            };
                            toastr.success(response.message);
                            $('#agentTable').DataTable().ajax.reload(null, false);
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
            let agentId = $(this).data('id');
            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('change.agent.status') }}",
                    data: {
                        agentId: agentId,
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
                        $('#agentTable').DataTable().ajax.reload(null, false);
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

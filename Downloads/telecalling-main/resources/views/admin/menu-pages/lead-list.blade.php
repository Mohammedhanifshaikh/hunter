@extends('layouts.master')
@section('title', 'Lead List - CRM')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header">Lead List</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="leadTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Company</th>
                            <th>Agent</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Lead Source</th>
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
            var leadTable = $("#leadTable").DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                bFilter: true,
                lengthChange: true,
                pageLength: 10,
                order: [],
                ajax: "{{ route('fetch.lead-list', $sheat_id) }}",
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
                        data: "agent",
                        name: "agent"
                    },

                    {
                        data: "name",
                        name: "name",
                        className: 'text-start'
                    },
                    {
                        data: "phone",
                        name: "phone"
                    },
                    {
                        data: "lead_source",
                        name: "lead_source"
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

        $(document).on("click", ".lead_delete_btn", function() {
            let leadId = $(this).data("id");
            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('delete.lead') }}",
                    data: {
                        leadId: leadId,
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                            };
                            toastr.success(response.message);
                            $('#leadTable').DataTable().ajax.reload(null, false);
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

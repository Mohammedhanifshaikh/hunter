@extends('layouts.master')
@section('title', 'Subscriptions')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Subscriptions</h5>
                <a href="{{ route('add.subscription') }}" class="btn btn-primary">Add Subscriptions</a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="subscriptionTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Duration</th>
                            <th>Price</th>
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
            var subscriptionTable = $("#subscriptionTable").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bFilter: true,
                lengthChange: true,
                pageLength: 100,
                lengthMenu: [
                    [100, 200, 300, 500, 1000],
                    [100, 200, 300, 500, 1000]
                ],
                order: [],
                ajax: "{{ route('fetch.subscription-list') }}", // The URL to fetch the data
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
                        data: "created_at",
                        name: "created_at",
                    },
                    {
                        data: "name",
                        name: "name",
                    },
                    {
                        data: "duration",
                        name: "duration",
                        className: 'text-start',
                    },
                    {
                        data: "price",
                        name: "price",
                        className: 'text-start',
                    },
                    {
                        data: "status",
                        name: "status",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    }
                ]
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on("click", ".subscription_delete_btn", function() {
            let subscriptionId = $(this).data("id");

            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('delete.subscription') }}",
                    data: {
                        subscriptionId: subscriptionId,
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.options = {
                                closeButton: true,
                                progressBar: true,
                            };
                            toastr.success(response.message);
                            $('#subscriptionTable').DataTable().ajax.reload(null, false); // Reload data
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
            let subscriptionId = $(this).data('id');
            if (confirm('Are you sure you want to change the status?')) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('change.subscription.status') }}",
                    data: {
                        subscriptionId: subscriptionId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true
                        };

                        if (response.status) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }

                        $('#subscriptionTable').DataTable().ajax.reload(null, false);
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

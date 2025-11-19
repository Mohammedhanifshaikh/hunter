@extends('layouts.master')
@section('title', 'Edit Sheet - CRM')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Sheet</h5>
                    </div>
                    <div class="card-body">
                        <form id="updateSheetForm">
                            @csrf
                            <div class="mb-3">
                                <input type="hidden" name="sheet_id" id="sheet_id" value="{{ $sheet->id }}">
                                <label class="form-label" for="company_name">Company Name</label>
                                <input type="text" class="form-control fix-word-first-letter" id="company_name"
                                    name="company_name" value="{{ $sheet->company->company_name }}" />
                                <span class="text-danger" id="company_name_error"></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="sheet_name">Sheet Name</label>
                                <input type="text" class="form-control fix-word-first-letter" id="sheet_name"
                                    name="sheet_name" value="{{ $sheet->sheat_name }}" />
                                <span class="text-danger" id="sheet_name_error"></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="agent_name">Agent(s)</label>
                                <ul class="list-group">
                                    @forelse($sheet->agent_list as $agent)
                                        <li class="list-group-item">{{ $agent->agent_name }}</li>
                                    @empty
                                        <li class="list-group-item text-muted">No agents assigned</li>
                                    @endforelse
                                </ul>
                            </div>


                            <div class="col-md">
                                <small class="form-label fw-medium d-block">Status</small>
                                @php
                                    $status = $sheet->status ?? null;
                                @endphp

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_approved"
                                        value="approved" {{ $status == 'approved' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_approved">Approved</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_pending"
                                        value="0" {{ $status == 'pending' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_pending">Pending</label>
                                </div>
                                <span class="text-danger" id="status_error"></span>
                            </div>
                            <div class="divider ">
                                <div class="divider-text"><i class="bx bx-crown"></i></div>
                            </div>

                            <button type="submit" class="btn btn-primary" id="updateSheetBtn">Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        $(document).ready(function() {

            function displayValidationError(field, error) {
                $('#' + field + '_error').text(error);
            }

            function clearFieldError(field) {
                $('#' + field + '_error').text('');
            }

            function attachClearErrorListeners(formId) {
                $(`${formId} input, ${formId} select, ${formId} textarea`).on('input change', function() {
                    var fieldName = $(this).attr('name');
                    clearFieldError(fieldName);
                });
            }


            $('#updateSheetForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                attachClearErrorListeners('#updateSheetForm');

                $.ajax({
                    method: "POST",
                    url: "{{ route('update.sheat') }}",
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $('#updateSheetBtn').attr('disabled', true);
                        $('#updateSheetBtn').html(
                            '<i class="fa fa-spinner fa-spin"></i> Processing...');
                    },
                    success: function(response) {
                        if (response.status === true) {
                            toastr.success(response.message);
                            setTimeout(() => {
                                if (response.redirect_url) {
                                    window.location.href = response.redirect_url;
                                }
                            }, 1000);
                        } else {
                            for (var field in response.errors) {
                                displayValidationError(field, response.errors[field][0]);
                            }
                        }
                    },
                    error: function(response) {
                        var errors = response.responseJSON.errors;
                        for (var field in errors) {
                            displayValidationError(field, errors[field][0]);
                        }
                    },
                    complete: function() {
                        $('#updateSheetBtn').attr('disabled', false);
                        $('#updateSheetBtn').html('Save changes');
                    }
                });
            });

            function displayValidationError(field, error) {
                $('#' + field + '_error').text(error);
            }

            function clearFieldError(field) {
                $('#' + field + '_error').text('');
            }
        });
    </script>
@endpush

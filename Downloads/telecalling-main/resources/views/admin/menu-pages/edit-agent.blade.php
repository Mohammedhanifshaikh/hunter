@extends('layouts.master')
@section('title', 'Edit Agent - CRM')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Agent</h5>
                    </div>
                    <div class="card-body">
                            <form id="updateAgentForm">
                                @csrf
                                <div class="mb-3">
                                    <input type="hidden" name="agent_id" id="agent_id"
                                        value="{{ $agent->id }}">
                                    <label class="form-label" for="company_name">Company Name</label>
                                    <input type="text" class="form-control fix-word-first-letter" id="company_name" name="company_name"
                                        value="{{ $agent->company->company_name }}" />
                                    <span class="text-danger" id="company_name_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="agent_name">Agent Name</label>
                                    <input type="text" class="form-control fix-word-first-letter" id="agent_name" name="agent_name"
                                        value="{{ $agent->agent_name }}" />
                                    <span class="text-danger" id="agent_name_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ $agent->phone }}" />
                                    <span class="text-danger" id="phone_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ $agent->email }}" />
                                    <span class="text-danger" id="email_error"></span>
                                </div>

                                 <div class="col-md">
                                    <small class="form-label fw-medium d-block">Status</small>
                                    @php
                                    $status = $agent->status ?? null;
                                    @endphp

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_approved" value="approved"
                                            {{ $status == 'approved' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_approved">Approved</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_pending"
                                            value="pending" {{ $status == 'pending' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_pending">Pending</label>
                                    </div>
                                </div>
                                <div class="divider ">
                                    <div class="divider-text"><i class="bx bx-crown"></i></div>
                                </div>

                                <button type="submit" class="btn btn-primary" id="updateAgentBtn">Update
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
                $(`${formId} input, ${formId} select, ${formId} textarea`).on('input change', function () {
                    var fieldName = $(this).attr('name');
                    clearFieldError(fieldName);
                });
            }


            $('#updateAgentForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                attachClearErrorListeners('#updateAgentForm');

                $.ajax({
                    method: "POST",
                    url: "{{ route('update.agent') }}",
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $('#updateAgentBtn').attr('disabled', true);
                        $('#updateAgentBtn').html(
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
                        $('#updateAgentBtn').attr('disabled', false);
                        $('#updateAgentBtn').html('Save changes');
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
@extends('layouts.master')
@section('title', 'Edit Company - CRM')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Company</h5>
                    </div>
                    <div class="card-body">
                            <form id="updateCompanyForm">
                                @csrf
                                <div class="mb-3">
                                    <input type="hidden" name="company_id" id="company_id"
                                        value="{{ $company->id }}">
                                    <label class="form-label" for="company_name">Company Name</label>
                                    <input type="text" class="form-control fix-word-first-letter" id="company_name" name="company_name"
                                        value="{{ $company->company_name }}" />
                                    <span class="text-danger" id="company_name_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="company_address">Company Address</label>
                                    <textarea class="form-control" id="company_address" name="company_address" rows="3">{{ $company->company_address }}</textarea>
                                    <span class="text-danger" id="company_address_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="pan_no">Pan No</label>
                                    <input type="text" class="form-control" id="pan_no" name="pan_no"
                                        value="{{ $company->pan_no }}" />
                                    <span class="text-danger" id="pan_no_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="adhaar_no">Adhaar No</label>
                                    <input type="text" class="form-control" id="adhaar_no" name="adhaar_no"
                                        value="{{ $company->adhaar_no }}" />
                                    <span class="text-danger" id="adhaar_no_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="mobile_no">Mobile No</label>
                                    <input type="number"
                                        class="form-control" id="mobile_no" name="mobile_no"
                                        value="{{ $company->mobile_no }}" />
                                    <span class="text-danger" id="mobile_no_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ $company->email }}" />
                                    <span class="text-danger" id="email_error"></span>
                                </div>

                                 <div class="col-md">
                                    <small class="form-label fw-medium d-block">Status</small>
                                    @php
                                    $status = $company->status ?? null;
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

                                <button type="submit" class="btn btn-primary" id="updateCompanyBtn">Update
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


            $('#updateCompanyForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                attachClearErrorListeners('#updateCompanyForm');

                $.ajax({
                    method: "POST",
                    url: "{{ route('update.company') }}",
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $('#updateCompanyBtn').attr('disabled', true);
                        $('#updateCompanyBtn').html(
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
                        $('#updateCompanyBtn').attr('disabled', false);
                        $('#updateCompanyBtn').html('Save changes');
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
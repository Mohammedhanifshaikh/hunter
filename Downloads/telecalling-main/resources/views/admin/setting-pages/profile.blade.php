@extends('layouts.master')
@section('title', 'Profile - CPanel')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <h4><span class="text-muted fw-light">Account Settings /</span> Account</h4> --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>
                    <hr class="my-0" />
                    <div class="card-body">
                        <form id="formAccountSettings">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">Full Name</label>
                                    <input class="form-control" type="text" id="name" name="name"
                                        value="{{ $user->name }}" autofocus placeholder="John" />
                                    <span class="text-danger" id="name_error"></span>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control" type="text" id="email" name="email"
                                        value="{{ $user->email }}" placeholder="john@example.com" />
                                    <span class="text-danger" id="email_error"></span>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input class="form-control" type="text" id="password" name="password" placeholder="********" />
                                    <span class="text-danger" id="password_error"></span>
                                </div>

                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-primary me-2" id="updateProfile">Save changes</button>
                            </div>
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
            $('#updateProfile').on('click', function(e) {
                e.preventDefault();
                var formData = new FormData($('#formAccountSettings')[0]);
                $('#formAccountSettings input').on('input', function() {
                    var fieldName = $(this).attr('name');
                    clearFieldError(fieldName);
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('update.profile') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $('#updateProfile').attr('disabled', true);
                        $('#updateProfile').html(
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
                        $('#updateProfile').attr('disabled', false);
                        $('#updateProfile').html('Save changes');
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

<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>CRM</title>
    <meta name="description" content="" />
    {{--
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/fonts/boxicons.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/pages/page-auth.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center">
                            <a href="{{ route('load.login') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo"><img
                                        src="{{ asset('admin/assets/img/avatars/1.png') }}"
                                        style="width:40px; border-radius:100%;" /></span>
                                <span class="app-brand-text demo menu-text fw-bold ms-2 text-capitalize">CRM</span>
                            </a>
                        </div>

                        <form id="formAuthentication" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email or Username</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email or username" autofocus />
                                <span class="text-danger" id="email_error"></span>
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>

                                <span class="text-danger" id="password_error"></span>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" id="submitLogin" type="submit">Sign
                                    in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
    {{--
    <script src="{{ asset('admin/assets/js/main.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
    $(document).ready(function() {
        $('#submitLogin').on('click', function(e) {
            e.preventDefault();
            var formData = new FormData($('#formAuthentication')[0]);
            $('#formAuthentication input').on('input', function() {
                var fieldName = $(this).attr('name');
                clearFieldError(fieldName);
            });

            $.ajax({
                method: "POST",
                url: "{{ route('post.login') }}",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $('#submitLogin').attr('disabled', true);
                    $('#submitLogin').html(
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
                    toastr.error(response.responseJSON.errors);
                    var errors = response.responseJSON.errors;
                },
                complete: function() {
                    $('#submitLogin').attr('disabled', false);
                    $('#submitLogin').html('Login');
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
</body>

</html>

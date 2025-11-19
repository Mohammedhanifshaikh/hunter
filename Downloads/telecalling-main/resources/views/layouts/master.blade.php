<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'WayToCure')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.section.style')
    <script type="text/javascript" src="{{ asset('admin/assets/vendor/js/helpers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/assets/js/config.js') }}"></script>
     <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/super-build/ckeditor.js"></script>

    @stack('custom-style')
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.section.sidebar')

            <div class="layout-page">

                @include('layouts.section.header')

                <div class="content-wrapper">
                    @yield('content')

                    @include('layouts.section.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    @include('layouts.section.script')
    @stack('custom-script')

</body>

</html>

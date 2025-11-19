<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'WayToCure')</title>
    <meta name="description" content="" />
    @include('layouts.section.style')
    {{-- Helpers --}}
    <script type="text/javascript" src="{{ asset('admin/assets/vendor/js/helpers.js') }}"></script>
    {{-- Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section --}}
    {{-- Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.   --}}
    <script type="text/javascript" src="{{ asset('admin/assets/js/config.js') }}"></script>
    @stack('custom-style')
</head>

<body>
    <!-- Content -->

    @yield('form-content')

    <!-- / Content -->

    @include('layouts.section.script')
    @stack('custom-script')
</body>

</html>

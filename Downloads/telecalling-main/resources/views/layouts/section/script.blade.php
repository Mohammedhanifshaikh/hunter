<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
{{-- <script type="text/javascript" src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('admin/assets/vendor/libs/popper/popper.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('admin/assets/vendor/js/bootstrap.js') }}"></script> --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

<script type="text/javascript" src="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}">
</script>
<script type="text/javascript" src="{{ asset('admin/assets/vendor/js/menu.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/assets/js/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/assets/js/dashboards-analytics.js') }}"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- DATATABLE --}}
<script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
{{-- <link rel="stylesheet" href="{{ asset('admin/assets/js/select2.css') }}" /> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

            function fixFirstLetterOnly(str) {
                return str.replace(/\b[a-z]/g, function (char) {
                    return char.toUpperCase();
                });
            }

            document.querySelectorAll('.fix-word-first-letter').forEach(input => {
                input.addEventListener('input', function () {
                    const cursorPos = this.selectionStart;
                    this.value = fixFirstLetterOnly(this.value);
                    this.setSelectionRange(cursorPos, cursorPos); // keeps typing smooth
                });
            });
    </script>

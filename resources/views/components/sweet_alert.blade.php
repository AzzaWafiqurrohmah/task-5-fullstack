<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session()->has('alert_s'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session()->get('alert_s') }}',
            timer: 1500,
        });
    </script>
@endif

@if(session()->has('alert_e'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session()->get('alert_e') }}',
            timer: 1500,
        });
    </script>
@endif

<script>
    $('#maintenance').on('click', function() {
        Swal.fire({
            icon: 'error',
            title: 'Maaf Fitur ini belum Tersedia',
            timer: 1500,
        });
    });
</script>
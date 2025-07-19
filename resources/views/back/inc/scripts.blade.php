<!-- Bootstrap JS -->
<script src="{{ url('back/assets/js/bootstrap.bundle.min.js') }}"></script>
<!--plugins-->
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace()
</script>
<script src="{{ url('back/assets/js/jquery.min.js') }}"></script>
<script src="{{ url('back/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ url('back/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
<script src="{{ url('back/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ url('back/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('back/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>

<!--app JS-->
<script src="{{ url('back/assets/js/app.js') }}"></script>

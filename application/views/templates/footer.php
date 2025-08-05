<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>


<script src="<?php echo base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/js/sb-admin-2.min.js'); ?>"></script>


<script src="<?php echo base_url('assets/vendor/chart.js/Chart.min.js'); ?>"></script>


<script src="<?php echo base_url('assets/js/demo/chart-area-demo.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/demo/chart-pie-demo.js'); ?>"></script>
<script>
    document.getElementById("currentYear").textContent = new Date().getFullYear();
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "destroy": true,
            "ordering": false,
            "paging": true,
            "searching": true,
            "columnDefs": [{
                "orderable": false,
                "targets": [0]
            }]
        });

        // Initialize Select2 inside modal
        $(document).ready(function() {
            $('#item_code, #item_code_med, #item_code_ppe').select2({
                dropdownParent: $('#myModal') // Change this to the actual modal ID
            });
        });

        // If inside a modal that loads dynamically:
        $(document).on('shown.bs.modal', function() {
            $('#item_code, #item_code_med, #item_code_ppe').each(function() {
                $(this).select2({
                    dropdownParent: $(this).closest('.modal')
                });
            });
        });

    });
</script>

<script>
    $(document).ready(function() {
        function initSelect2() {

            $('#student_names, #return_btch').select2({
                placeholder: "Select a Student",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#student_names, #return_btch').closest('.modal'), // Ensure dropdown stays inside the modal
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });
            // generate reports select2 for borrowed books
            $('#student_name').select2({
                placeholder: "Select a Student",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#student_name').closest('.modal'), // Ensure dropdown stays inside the modal
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });

            $('#return_btchs').select2({
                placeholder: "Select a Student",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#return_btchs').closest('.modal'), // Ensure dropdown stays inside the modal
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });
        }

        // Initialize Select2 when the document loads
        initSelect2();

        // Ensure Select2 works inside the modal
        $(document).on('shown.bs.modal', function() {
            initSelect2();
        });
    });
</script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $this->session->flashdata('success'); ?>',
                showConfirmButton: false,
                timer: 2000
            });
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '<?= $this->session->flashdata('error'); ?>',
                showConfirmButton: true
            });
        <?php endif; ?>
    });
</script>

</body>

</html>
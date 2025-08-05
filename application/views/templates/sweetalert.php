<?php if ($this->session->flashdata('success')): ?>
        <script>
            Swal.fire({
                title: 'Success!',
                text: '<?= $this->session->flashdata('success') ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
<?php elseif ($this->session->flashdata('error')): ?>
        <script>
            Swal.fire({
                title: 'Error!',
                text: '<?= $this->session->flashdata('error') ?>',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
<?php endif; ?>
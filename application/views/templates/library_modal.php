<!-- Add Modal -->
<div class="modal modal-md radius fade" id="addBook" tabindex="-1" aria-labelledby="addBookLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addBookLabel">ADD - BOOK</h5>
                <button type="button" class="btn-close bg-white text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('library/store'); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" class="form-control" name="barcode" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Book Title</label>
                                <input type="text" class="form-control" name="book_title" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Author</label>
                                <input type="text" class="form-control" name="author" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Classification</label>
                                <input type="text" class="form-control" name="classification" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Classification Number</label>
                                <input type="text" class="form-control" name="classification_number" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category Subject</label>
                                <input type="text" class="form-control" name="category_subject" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ISBN</label>
                                <input type="text" class="form-control" name="isbn" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Year Published</label>
                                <input type="text" class="form-control" name="year_published" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Language</label>
                                <input type="text" class="form-control" name="language" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date Purchased</label>
                                <input type="date" class="form-control" name="date_purchased" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date Entered</label>
                                <input type="date" class="form-control" name="date_entered" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Transaction Type</label>
                                <select class="form-control" name="transaction_type" required>
                                    <option value="Purchase">Purchase</option>
                                    <option value="Donation">Donation</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="Available">Available</option>
                                    <option value="Borrowed">Borrowed</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>




<!-- Update Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="editBookForm" action="<?= base_url('library/update') ?>" method="post">
               <input type="hidden" name="id" id="edit_id">
               
               <div class="mb-3">
                  <label for="edit_date_purchased" class="form-label">Date Purchased</label>
                  <input type="date" class="form-control" id="edit_date_purchased" name="date_purchased" required>
               </div>

               <div class="mb-3">
                  <label for="edit_date_entered" class="form-label">Date Entered</label>
                  <input type="date" class="form-control" id="edit_date_entered" name="date_entered" required>
               </div>

               <div class="mb-3">
                  <label for="edit_transaction_type" class="form-label">Transaction Type</label>
                  <input type="text" class="form-control" id="edit_transaction_type" name="transaction_type" required>
               </div>

               <div class="mb-3">
                  <label for="edit_book_title" class="form-label">Book Title</label>
                  <input type="text" class="form-control" id="edit_book_title" name="book_title" required>
               </div>

               <div class="mb-3">
                  <label for="edit_classification" class="form-label">Classification</label>
                  <input type="text" class="form-control" id="edit_classification" name="classification" required>
               </div>

               <div class="mb-3">
                  <label for="edit_classification_number" class="form-label">Classification Number</label>
                  <input type="text" class="form-control" id="edit_classification_number" name="classification_number" required>
               </div>

               <div class="mb-3">
                  <label for="edit_author" class="form-label">Author</label>
                  <input type="text" class="form-control" id="edit_author" name="author" required>
               </div>

               <div class="mb-3">
                  <label for="edit_subject" class="form-label">Subject</label>
                  <input type="text" class="form-control" id="edit_subject" name="subject" required>
               </div>

               <div class="mb-3">
                  <label for="edit_category_subject" class="form-label">Category Subject</label>
                  <input type="text" class="form-control" id="edit_category_subject" name="category_subject" required>
               </div>

               <div class="mb-3">
                  <label for="edit_isbn" class="form-label">ISBN</label>
                  <input type="text" class="form-control" id="edit_isbn" name="isbn" required>
               </div>

               <div class="mb-3">
                  <label for="edit_barcode" class="form-label">Barcode</label>
                  <input type="text" class="form-control" id="edit_barcode" name="barcode" required>
               </div>

               <div class="mb-3">
                  <label for="edit_year_published" class="form-label">Year Published</label>
                  <input type="number" class="form-control" id="edit_year_published" name="year_published" required>
               </div>

               <div class="mb-3">
                  <label for="edit_language" class="form-label">Language</label>
                  <input type="text" class="form-control" id="edit_language" name="language" required>
               </div>

               <div class="mb-3">
                  <label for="edit_status" class="form-label">Status</label>
                  <select class="form-control" id="edit_status" name="status" required>
                     <option value="Available">Available</option>
                     <option value="Borrowed">Borrowed</option>
                     <option value="Disposed">Dispose</option>
                  </select>
               </div>

               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>
    $(document).ready(function () {
    $('.updateBtn').on('click', function () {
        $('#edit_id').val($(this).data('id'));
        $('#edit_date_purchased').val($(this).data('date_purchased'));
        $('#edit_date_entered').val($(this).data('date_entered'));
        $('#edit_transaction_type').val($(this).data('transaction_type'));
        $('#edit_book_title').val($(this).data('book_title'));
        $('#edit_classification').val($(this).data('classification'));
        $('#edit_classification_number').val($(this).data('classification_number'));
        $('#edit_author').val($(this).data('author'));
        $('#edit_subject').val($(this).data('subject'));
        $('#edit_category_subject').val($(this).data('category_subject'));
        $('#edit_isbn').val($(this).data('isbn'));
        $('#edit_barcode').val($(this).data('barcode'));
        $('#edit_year_published').val($(this).data('year_published'));
        $('#edit_language').val($(this).data('language'));
        $('#edit_status').val($(this).data('status'));

        $('#editBookModal').modal('show');
    });
});

</script>





<!-- success or error -->
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
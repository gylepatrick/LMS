
 
<div class="modal fade" id="acqModalLibrary" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger text-white">
                <h6 class="modal-title" id="addBookModalLabel"><i class="fas fa-file-alt"></i> Library Inventory Report</h6>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-primary">Select filters to generate a report. Leave blank for all records.</p>
                <form action="<?= base_url('export_library/generateReport') ?>" method="GET"> 
                    
                    <label for="transaction_type text-primary"><i class="fas fa-exchange-alt"></i> Transaction Type</label>
                    <select name="transaction_type" class="form-control">
                        <option value="">All</option>
                        <option value="Purchase">Acquisition/Purchase</option>
                        <option value="Donation">Donation</option>
                        <option value="Disposal">Disposal</option>
                    </select>

                    <label for="classification text-primary"><i class="fas fa-book"></i> Classification</label>
                    <select name="classification" class="form-control">
                        <option value="">All</option>
                        <?php foreach ($classifications as $class): ?>
                        <option value="<?php echo $class->classification ?>"><?php echo $class->classification ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <label for="period text-primary"><i class="fas fa-calendar-alt"></i> Period</label>
                    <select name="period" class="form-control" id="period_select">
                        <option value="">All</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="custom">Custom</option>
                    </select>

                    <script>
    document.getElementById('period_select').addEventListener('change', function() {
        if (this.value === 'custom') {
            document.getElementById('custom_dates').style.display = 'block';
        } else {
            document.getElementById('custom_dates').style.display = 'none';
        }
    });
</script>

                    <div id="custom_dates text-primary" style="display: none;">
                        <label for="start_date">From:</label>
                        <input type="date" class="form-control" name="start_date" >
                        <label for="end_date">To:</label>
                        <input type="date" class="form-control" name="end_date" >
                    </div>
                    
                    <button class="btn btn-sm btn-danger text-white mt-3" type="submit"><i class="fas fa-download"></i> DOWNLOAD EXCEL</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="col-12">
    <div class="card bg-white shadow rounded">
        <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-book"></i> Library Inventory</h4>
            <div>
                <button class="btn btn-secondary btn-sm" title="Open Generate Reports Modal" data-bs-toggle="modal" data-bs-target="#acqModalLibrary">
                    <i class="fas fa-file-excel"></i> Generate Report
                </button>
                <button class="btn btn-success btn-sm" title="Open New Book Entry Modal" data-bs-toggle="modal" data-bs-target="#addBookNow">
                    <i class="fas fa-file-alt"></i> New Book
                </button>
            </div>
        </div>
        <div class="card-body">
    <div class="table-responsive">
    <table id="example" class="table table-hover text-center align-middle">
    <thead class="table-light border-0 shadow-sm">
        <tr class="text-dark">
            <th><i class="fas fa-calendar-day"></i> Date</th>
            <th><i class="fas fa-exchange-alt"></i> Transaction</th>
            <th><i class="fas fa-book"></i> Book Title</th>
            <th><i class="fas fa-layer-group"></i> Classification</th>
            <th><i class="fas fa-user"></i> Author</th>
            <th><i class="fas fa-barcode"></i> ISBN</th>
            <th><i class="fas fa-sort-numeric-up"></i> Quantity</th>
            <th><i class="fas fa-peso-sign"></i> Unit Cost</th>
            <th><i class="fas fa-peso-sign"></i> Acq. Cost</th>
            <th><i class="fas fa-info-circle"></i> Status</th>
            <th><i class="fas fa-edit"></i> Action</th>
        </tr>
    </thead>
    <tbody class="table-group-divider">
        <?php foreach ($books as $book): ?>
        <tr>
            <td><?= $book->date_entered ?></td>
            <td class="fw-semibold"><?= htmlspecialchars($book->transaction_type) ?></td>
            <td class="fw-semibold"><?= htmlspecialchars($book->book_title) ?></td>
            <td><?= htmlspecialchars($book->classification) ?></td>
            <td><?= htmlspecialchars($book->author) ?></td>
            <td><?= htmlspecialchars($book->isbn) ?></td>
            <td class="text-primary"><?= $book->quantity ?></td>
            <td>₱<?= number_format($book->unit_cost, 2) ?></td>
            <td>₱<?= number_format($book->acq_cost, 2) ?></td>
            <td>
                <?php if ($book->status == "Borrowed"): ?>
                    <span class="badge bg-danger bg-opacity-75 rounded-pill px-3 py-2">
                        <i class="fas fa-times-circle"></i> Borrowed
                    </span>
                <?php else: ?>
                    <span class="badge bg-success bg-opacity-75 rounded-pill px-3 py-2 text-white">
                        <i class="fas fa-check-circle"></i> Available
                    </span>
                <?php endif; ?>
            </td>
            <td>
                <button class="btn btn-sm btn-secondary updateBtn" title="Edit Entry"
                    data-bs-toggle="modal" data-bs-target="#editBookModal"
                    data-id="<?= $book->id ?>"
                    data-date_purchased="<?= $book->date_purchased ?>"
                    data-date_entered="<?= $book->date_entered ?>"
                    data-transaction_type="<?= htmlspecialchars($book->transaction_type) ?>"
                    data-book_title="<?= htmlspecialchars($book->book_title) ?>"
                    data-classification="<?= htmlspecialchars($book->classification) ?>"
                    data-classification_number="<?= htmlspecialchars($book->classification_number) ?>"
                    data-author="<?= htmlspecialchars($book->author) ?>"
                    data-subject="<?= htmlspecialchars($book->subject) ?>"
                    data-category_subject="<?= htmlspecialchars($book->category_subject) ?>"
                    data-isbn="<?= htmlspecialchars($book->isbn) ?>"
                    data-barcode="<?= htmlspecialchars($book->barcode) ?>"
                    data-year_published="<?= $book->year_published ?>"
                    data-language="<?= htmlspecialchars($book->language) ?>"
                    data-status="<?= htmlspecialchars($book->status) ?>">
                    <i class="fas fa-edit"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    </div>
</div>

    </div>
</div>

<script>
    document.getElementById('period').addEventListener('change', function() {
        if (this.value === 'custom') {
            document.getElementById('custom-date-range').style.display = 'block';
        } else {
            document.getElementById('custom-date-range').style.display = 'none';
        }
    });
</script>

<!-- add modal -->
<div class="modal fade" id="addBookNow" tabindex="-1" aria-labelledby="addBookLabel" aria-hidden="true">
    <div class="modal-dialog modal-md"> 
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger text-white">
                <h5 class="modal-title" id="addBookLabel">
                    <i class="fas fa-book"></i> ADD BOOK
                </h5>
                <button type="button" class="btn-close bg-white text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('library/store'); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-barcode"></i> Barcode</label>
                                <input type="text" class="form-control" name="barcode" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-book"></i> Book Title</label>
                                <input type="text" class="form-control" name="book_title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-user"></i> Author</label>
                                <input type="text" class="form-control" name="author" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-folder"></i> Classification</label>
                                <input type="text" class="form-control" name="classification" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-hashtag"></i> Classification Number</label>
                                <input type="text" class="form-control" name="classification_number" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-book-open"></i> Subject</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-list"></i> Category Subject</label>
                                <input type="text" class="form-control" name="category_subject" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-exchange-alt"></i> Transaction Type</label>
                                <select class="form-control" name="transaction_type" required>
                                    <option value="Purchase">Purchase</option>
                                    <option value="Donation">Donation</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-barcode"></i> ISBN</label>
                                <input type="text" class="form-control" name="isbn" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-calendar"></i> Year Published</label>
                                <input type="text" class="form-control" name="year_published" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-language"></i> Language</label>
                                <input type="text" class="form-control" name="language" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-calendar-alt"></i> Date Purchased</label>
                                <input type="date" class="form-control" name="date_purchased" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-calendar-check"></i> Date Entered</label>
                                <input type="date" class="form-control" name="date_entered" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-sort-numeric-up"></i> Quantity</label>
                                <input type="number" class="form-control" name="quantity" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-money-bill"></i> Unit Cost</label>
                                <input type="number" class="form-control" name="unit_cost" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-calculator"></i> Acquisition Cost</label>
                                <input type="number" class="form-control" name="acq_cost" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-check-circle"></i> Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="Available">Available</option>
                                    <option value="Borrowed">Borrowed</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-gradient-danger text-white">
            <h5 class="modal-title" id="editBookModalLabel"><i class="fas fa-edit"></i> Edit Book</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="editBookForm" action="<?= base_url('library/update') ?>" method="post">
               <input type="hidden" name="id" id="edit_id">
               
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="edit_date_purchased" class="form-label"><i class="fas fa-calendar-alt"></i> Date Purchased</label>
                        <input type="date" class="form-control" id="edit_date_purchased" name="date_purchased" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_date_entered" class="form-label"><i class="fas fa-calendar-check"></i> Date Entered</label>
                        <input type="date" class="form-control" id="edit_date_entered" name="date_entered" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_transaction_type" class="form-label"><i class="fas fa-exchange-alt"></i> Transaction Type</label>
                        <input type="text" class="form-control" id="edit_transaction_type" name="transaction_type" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_book_title" class="form-label"><i class="fas fa-book"></i> Book Title</label>
                        <input type="text" class="form-control" id="edit_book_title" name="book_title" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_classification" class="form-label"><i class="fas fa-layer-group"></i> Classification</label>
                        <input type="text" class="form-control" id="edit_classification" name="classification" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_classification_number" class="form-label"><i class="fas fa-sort-numeric-up"></i> Classification Number</label>
                        <input type="text" class="form-control" id="edit_classification_number" name="classification_number" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_author" class="form-label"><i class="fas fa-user"></i> Author</label>
                        <input type="text" class="form-control" id="edit_author" name="author" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_subject" class="form-label"><i class="fas fa-book-open"></i> Subject</label>
                        <input type="text" class="form-control" id="edit_subject" name="subject" required>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="edit_category_subject" class="form-label"><i class="fas fa-tag"></i> Category Subject</label>
                        <input type="text" class="form-control" id="edit_category_subject" name="category_subject" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_isbn" class="form-label"><i class="fas fa-barcode"></i> ISBN</label>
                        <input type="text" class="form-control" id="edit_isbn" name="isbn" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_barcode" class="form-label"><i class="fas fa-qrcode"></i> Barcode</label>
                        <input type="text" class="form-control" id="edit_barcode" name="barcode" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_year_published" class="form-label"><i class="fas fa-calendar"></i> Year Published</label>
                        <input type="number" class="form-control" id="edit_year_published" name="year_published" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_language" class="form-label"><i class="fas fa-language"></i> Language</label>
                        <input type="text" class="form-control" id="edit_language" name="language" required>
                     </div>
                     <div class="mb-3">
                        <label for="edit_status" class="form-label"><i class="fas fa-info-circle"></i> Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                           <option value="Available">Available</option>
                           <option value="Borrowed">Borrowed</option>
                           <option value="Disposed">Dispose</option>
                        </select>
                     </div>
                  </div>
               </div>
               
               <div class="modal-footer">
                  <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                  <button type="submit" class="btn-sm btn  btn-danger"><i class="fas fa-save"></i> Save Changes</button>
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
        $('#edit_publisher').val($(this).data('publisher'));
        $('#edit_edition').val($(this).data('edition'));
        $('#edit_status').val($(this).data('status'));
        $('#editBookModal').modal('show');
    });
});
</script>

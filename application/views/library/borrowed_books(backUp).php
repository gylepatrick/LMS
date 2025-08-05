
<!-- Modal for Generating Reports with Filters -->
<div class="modal fade" id="acqModalLibrarySub" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-dark">
            <div class="modal-header bg-gradient-danger text-white">
                <h6 class="modal-title"><i class="fas fa-file-alt"></i> Library Inventory Report (Borrowed)</h6>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-dark">Select filters to generate a report. Leave blank for all records.</p>
                <form id="reportFilterForm" class="text-dark"> 
                    <label for="student_name" class="text-dark"><i class="fas fa-user text-dark"></i> Select Student</label>
                    <select name="student_name" id="student_name" class="form-control text-dark">
                        <option class="text-dark" value="">All</option>
                        <?php foreach ($students as $student): ?>
                            <option class="text-dark" value="<?= $student->fullname ?>"><?= htmlspecialchars($student->fullname) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="button" id="previewReport" class="btn btn-secondary text-white mt-3">
                        <i class="fas fa-eye"></i> PREVIEW
                    </button>

                    <button class="btn btn-danger text-white mt-3" type="submit">
                        <i class="fas fa-download"></i> DOWNLOAD EXCEL
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-white text-dark">
                <h6 class="modal-title"><i class="fas fa-file-alt"></i> Report Preview</h6>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Student Details</h6>
                <p><strong>Name:</strong> <span id="previewName">N/A</span></p>
                <p><strong>ID Number:</strong> <span id="previewID">N/A</span></p>
                <p><strong>Course:</strong> <span id="previewCourse">N/A</span></p>
                <p><strong>Year:</strong> <span id="previewYear">N/A</span></p>

                <h6>Borrowed Books</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date Borrowed</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Barcode</th>
                            <th>Due Date</th>
                            <th>Date Returned</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="previewTableBody">
                        <tr><td colspan="7" class="text-center">No data available</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- mostBorrowesModal -->
<div class="modal fade" id="mostBorrowedModal" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger text-white">
                <h6 class="modal-title"><i class="fas fa-file-alt"></i> Library Inventory Report (Most Botrrowed Books)</h6>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small">Select filters to generate a report. Leave blank for all records.</p>
                <form  action="<?= base_url('export_library/generateReportMostBorrowed') ?>"> 
                    <label for="student_name"><i class="fas fa-book"></i> Choose Filter for </label>
                    <select name="top" id="top" class="form-control">
                        <option value="">All</option>
                        <option value="70">Top 70</option>
                        <option value="50">Top 50</option>
                        <option value="20">Top 20</option>
                        <option value="10">Top 10</option>
                    </select>

                    <button class="btn btn-danger text-white mt-3" type="submit">
                        <i class="fas fa-download"></i> DOWNLOAD EXCEL
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="card shadow-sm">
    <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-book-reader"></i> Borrowed Books</h5>
        <div>
            <a class="btn btn-sm btn-secondary text-light px-3 shadow-sm" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-file-excel"></i> Generate Reports
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="btn btn-sm px-3" data-bs-toggle="modal" data-bs-target="#acqModalLibrarySub"> <i class="fas fa-file-excel"></i> Borrowed Books</a></li>
                <li><a class="btn btn-sm  px-3" data-bs-toggle="modal" data-bs-target="#mostBorrowedModal">
                <i class="fa-solid fa-file-excel"></i> MOST BORROWED BOOKS
            </a></li>
            </ul>
            <a class="btn btn-sm btn-danger text-light px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#borrowBook">
                <i class="fa-solid fa-hand-holding"></i> Borrow
            </a>
            <a class="btn btn-sm btn-success text-light px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#returnBook">
                <i class="fa-solid fa-undo"></i> Return
            </a>

        </div>
    </div>
    
    <div class="card-body">
    <div class="table-responsive">
        <table id="example" class="table table-hover align-middle">
            <thead class="table-light border-0 shadow-sm">
                <tr class="text-dark">
                    <th><i class="fas fa-calendar-alt"></i> Date Borrowed</th>
                    <th><i class="fas fa-book"></i> Title</th>
                    <th><i class="fas fa-user"></i> Author</th>
                    <th><i class="fas fa-barcode"></i> ISBN</th>
                    <th><i class="fas fa-qrcode"></i> Barcode</th>
                    <th><i class="fas fa-calendar-day"></i> Due Date</th>       
                    <th><i class="fas fa-calendar-check"></i> Date Returned</th>
                    <th><i class="fas fa-user"></i> Borrower</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php foreach ($borrowed_books as $book): ?>
                    <tr class="align-middle">
                        <td><?= $book->borrow_date ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($book->book_title) ?></td>
                        <td><?= htmlspecialchars($book->book_author) ?></td>
                        <td><?= htmlspecialchars($book->isbn) ?></td>
                        <td><?= htmlspecialchars($book->barcode) ?></td>
                        <td class="text-primary"><?= $book->return_date ?></td>
                        <td>
                            <?= $book->actual_returned_date 
                                ? '<span class="text-success text-white fw-bold">' . $book->actual_returned_date . '</span>' 
                                : '<span class="text-danger text-white fw-bold">Not Returned</span>' ?>
                        </td>
                        <td><?= $book->student_name ?></td>
                        <td>
                            <?php if (!$book->actual_returned_date): ?>
                                <span class="badge bg-danger text-white bg-opacity-75 rounded-pill px-3 py-2">
                                    <i class="fas fa-times-circle"></i> Not Returned
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success text-white     bg-opacity-75 rounded-pill px-3 py-2">
                                    <i class="fas fa-check-circle"></i> Returned
                                </span>
                            <?php endif; ?>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</div>


<!-- Return of Books Form Modal -->
<div class="modal fade" id="returnBook" tabindex="-1" aria-labelledby="returnBookLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white border-lg shadow-xl">
                <h5 class="modal-title"><i class="fas fa-undo-alt"></i> Return a Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="returnForm" method="POST" action="<?= site_url('library/return_book') ?>">
                    <h4><i class="fas fa-book"></i> Book Information</h4>
                    
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-users"></i> Student Name</label>
                        <select class="form-control select2" id="return_btch" name="return_btch" required>
                            <option value="">Select Borrow Code</option>
                            <?php foreach ($brrw_nos as $brrw_no): ?>
                                <option value="<?= $brrw_no->borrow_btch ?>" data-name="<?= htmlspecialchars($brrw_no->borrow_btch) ?>">
                                <?= htmlspecialchars($brrw_no->book_title) ?> - <?= htmlspecialchars($brrw_no->student_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-barcode"></i> Book Barcode</label>
                        <input type="text" class="form-control" id="return_barcode" name="return_barcode" autofocus>
                    </div>

                    <input type="hidden" id="return_book_id" name="return_book_id">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-book-open"></i> Title</label>
                        <input type="text" class="form-control" id="return_book_title" name="return_book_title" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-book-open"></i> Quantity</label>
                        <input type="text" class="form-control" id="return_quantity" name="return_quantity" readonly>
                    </div>

                    <h4><i class="fas fa-user"></i> Borrower Information</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-calendar-alt"></i> Borrowed Date</label>
                            <input type="text" class="form-control" id="borrowed_date" name="borrowed_date" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-calendar-day"></i> Due Date</label>
                            <input type="text" class="form-control" id="due_date" name="due_date" readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-calendar-check"></i> Actual Return Date</label>
                        <input type="date" class="form-control" id="actual_return_date" name="actual_return_date" required>
                    </div>

                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                        <div>
                            <small> Return date is auto generated.</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success text-white"><i class="fas fa-check-circle"></i> Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#return_btch').on('change', function() {
        var return_btch = $(this).val();
        var barcode = $('#return_barcode').val();

        if (return_btch) {
            $.ajax({
                url: "<?= site_url('library/get_borrowed_book_by_barcode') ?>",
                type: "POST",
                data: { return_barcode: barcode, return_btch: return_btch },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        $('#return_student_name').val(response.data.student_name);
                        $('#return_book_id').val(response.data.id);
                        $('#return_book_title').val(response.data.book_title);
                        $('#return_book_author').val(response.data.author);
                        $('#borrowed_date').val(response.data.borrowed_date);
                        $('#due_date').val(response.data.due_date);
                        $('#return_quantity').val(response.data.quantity);
                        $('#borrowed_book_id').val(response.data.id);
                        $('#actual_return_date').val(new Date().toISOString().split('T')[0]);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching book details.');
                    console.log(xhr.responseText);
                }
            });
        }
    });
});

</script>

<!-- Borrowing of Books Form Modal -->
<div class="modal fade" id="borrowBook" tabindex="-1" aria-labelledby="borrowBookLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger text-white border-sm shadow-xl">
                <h5 class="modal-title"><i class="fas fa-book-reader"></i> Borrow a Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="borrowForm" method="POST" action="<?= site_url('library/borrow_book') ?>">
                    <h4><i class="fas fa-book"></i> Book Information</h4>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-barcode"></i> Scan Barcode</label>
                        <input type="text" class="form-control" id="barcode" name="barcode" autofocus>
                    </div>
                    <input type="hidden" id="book_id" name="book_id">
                    <div class="book-info" id="book-info">
                        <div class="mb-3">
                        <label class="form-label"><i class="fas fa-book-open"></i> Title</label>
                        <input type="text" class="form-control" id="book_title" name="book_title" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user-edit"></i> Author</label>
                        <input type="text" class="form-control" id="book_author" name="book_author" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-barcode"></i> ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" readonly>
                    </div>
                    </div>
                    <input type="hidden" id="book_status" name="book_status">

                    <h4><i class="fas fa-user"></i> Student/Borrower Information</h4>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-users"></i> Student Name</label>
                        <select class="form-control select2" id="student_names" name="student_name" required>
                            <option value="">Select Student</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student->fullname ?>" data-name="<?= htmlspecialchars($student->fullname) ?>">
                                    <?= htmlspecialchars($student->fullname) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-calendar-alt"></i> Borrow Date</label>
                            <input type="date" class="form-control" id="borrow_date" name="borrow_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-calendar-day"></i> Due Date</label>
                            <input type="date" class="form-control" id="return_date" name="return_date" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="hidden" class="form-control" id="borrowed_number" name="borrowed_number" value="1" required>
                    </div>


                    <button type="submit" class="btn btn-danger" id="btn-submit"><i class="fas fa-plus-circle"></i> Borrow</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    let $bookInfo = $('#book-info');
    let getBookUrl = "<?= site_url('library/get_book_by_barcode') ?>";

    // Initially hide the book info section
    $bookInfo.hide();

    $('#barcode').on('change', function() {
        let barcode = $(this).val().trim();

        if (barcode !== '') {
            // Optional: Disable input to indicate loading
            $(this).prop('disabled', true);

            $.ajax({
                url: getBookUrl,
                type: "POST",
                data: { barcode: barcode },
                dataType: "json",
                success: function(response) {
                    console.log(response); // For debugging

                    if (response.status === 'success' && response.data) {
                        $bookInfo.show();

                        $('#book_id').val(response.data.id);
                        $('#book_title').val(response.data.book_title);
                        $('#book_author').val(response.data.author);
                        $('#isbn').val(response.data.isbn);
                        $('#book_status').val(response.data.status);
                        $('#quantity').val(response.data.quantity);

                        if (response.data.status !== 'Available') { 
                            $('#btn-submit').prop('disabled', true);
                            alert('This book is not available for borrowing.');
                            location.reload();
                        } else {
                            $('#btn-submit').prop('disabled', false);
                        }
                    } else {
                        alert('Book not found!');
                        $('#book_id, #book_title, #book_author, #isbn, #book_status, #quantity').val('');
                        $('#btn-submit').prop('disabled', true);
                        $bookInfo.hide();
                    }
                },
                error: function() {
                    alert('Error fetching book details.');
                    $bookInfo.hide();
                },
                complete: function() {
                    // Re-enable the barcode input
                    $('#barcode').prop('disabled', false);
                }
            });
        }
    });

    $('#student_id').on('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        $('#student_name').val(selectedOption.getAttribute('data-name') || '');
    });
});
</script>



<script>
$(document).ready(function() {
    $("#previewReport").click(function() {
        var studentId = $("#student_name").val();

        $.ajax({
            url: "<?= base_url('export_library/previewReportBorrowed') ?>",
            type: "GET",
            data: { student_name: studentId },
            dataType: "json",
            success: function(response) {
                $("#previewName").text(response.student.fullname ?? "N/A");
                $("#previewID").text(response.student.student_id ?? "N/A");
                $("#previewCourse").text(response.student.course ?? "N/A");
                $("#previewYear").text(response.student.year ?? "N/A");
                $("#previewTableBody").empty();

                if (response.transactions.length > 0) {
                    $.each(response.transactions, function(index, transaction) {
                        $("#previewTableBody").append(`
                            <tr>
                                <td>${transaction.borrow_date}</td>
                                <td>${transaction.book_title}</td>
                                <td>${transaction.book_author}</td>
                                <td>${transaction.barcode}</td>
                                <td>${transaction.return_date}</td>
                                <td>${transaction.actual_returned_date ?? "Not Yet Returned"}</td>
                                <td>${transaction.status}</td>
                            </tr>
                        `);
                    });
                } else {
                    $("#previewTableBody").html('<tr><td colspan="7" class="text-center">No data available</td></tr>');
                }

                $("#previewModal").modal("show");
            },
            error: function() {
                alert("Failed to fetch report data.");
            }
        });
    });

    $("#reportFilterForm").submit(function() {
        $(this).attr("action", "<?= base_url('export_library/generateReportBorrowed') ?>");
    });
});
</script>

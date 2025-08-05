<input type="hidden" name="schl_name" value="<?= $this->session->userdata('school'); ?>">
<input type="hidden" name="schl_address" value="<?= $this->session->userdata('school_address'); ?>">
<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-header">
                <h5>Search Book</h5>
            </div>
            <div class="card-body">

                <!-- Search Bar -->
                <div class="input-group mb-3 shadow-sm">
                    <input type="text" id="searchInput" class="form-control border-primary" placeholder="Search to filter results ...">
                    <button class="btn btn-primary" id="searchBtn" title="Search">
                        <i class="fas fa-search"></i>
                    </button>
                </div>


                <!-- Action Buttons -->
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-light btn-sm shadow-sm" id="generateReportBtn">
                        <i class="fas fa-file-excel"></i> Download Results
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div class="col-9">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive shadow-sm rounded">
                    <table id="booksTable" class="table table-striped table-hover align-middle">
                        <thead class="table-primary text-white">
                            <tr>
                                <th><i class="fas fa-book"></i> Title</th>
                                <th><i class="fas fa-user"></i> Author</th>
                                <th><i class="fas fa-layer-group"></i> Classification</th>
                                <th><i class="fas fa-book"></i> Subject</th>
                                <th><i class="fas fa-book"></i> Subject Category</th>
                                <th><i class="fas fa-barcode"></i> ISBN</th>
                                <th><i class="fas fa-calendar"></i> Year</th>
                                <th><i class="fas fa-globe"></i> Language</th>
                            </tr>
                        </thead>
                        <tbody id="booksBody">
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($book->book_title) ?></td>
                                    <td><?= htmlspecialchars($book->author) ?></td>
                                    <td><?= htmlspecialchars($book->classification) ?></td>
                                    <td><?= htmlspecialchars($book->subject) ?></td>
                                    <td><?= htmlspecialchars($book->category_subject) ?></td>
                                    <td><?= htmlspecialchars($book->isbn) ?></td>
                                    <td><?= htmlspecialchars($book->year_published) ?></td>
                                    <td><?= htmlspecialchars($book->language) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <style>
            .dataTables_filter {
                display: none;
            }
        </style>

        <script>
            $(document).ready(function() {
                // Initialize DataTable with search enabled
                var table = $('#booksTable').DataTable({
                    "ordering": true,
                    "paging": true,
                    "searching": true // Enable built-in search
                });

                // Custom Search Using DataTables API
                $("#searchInput").on("keyup", function() {
                    table.search(this.value).draw(); // Live search as user types
                });

                // AJAX Search Button
                $("#searchBtn").click(function() {
                    var query = $("#searchInput").val();

                    $.ajax({
                        url: "<?= base_url('library/search_books') ?>",
                        method: "POST",
                        data: {
                            query: query
                        },
                        dataType: "json",
                        success: function(response) {
                            var tableBody = $("#booksBody");
                            tableBody.empty();

                            if (response.length > 0) {
                                response.forEach(function(book) {
                                    tableBody.append(`
                            
                                <tr>
                                    <td class="fw-semibold">${book.book_title}</td>
                                    <td>${book.author}</td>
                                    <td>${book.classification}</td>
                                    <td>${book.subject}</td>
                                    <td>${book.category_subject}</td>
                                    <td>${book.isbn}</td>
                                    <td>${book.year_published}</td>
                                    <td>${book.language}</td>
                                </tr>
                            `);
                                });
                            } else {
                                tableBody.append(`<tr><td colspan="8" class="text-center text-danger">No books found</td></tr>`);
                            }

                            // Refresh DataTable after AJAX update
                            table.clear().destroy();
                            table = $('#booksTable').DataTable({
                                "ordering": true,
                                "paging": true,
                                "searching": true // Keep search enabled
                            });
                        },
                        error: function() {
                            alert("Error fetching books.");
                        },
                    });
                });

                // Generate Report Button
                $("#generateReportBtn").click(function() {
                    var query = $("#searchInput").val();
                    var schlName = $("input[name='schl_name']").val();
                    var schlAddress = $("input[name='schl_address']").val();

                    var url = "<?= base_url('export_library/generate_excel') ?>" +
                        "?query=" + encodeURIComponent(query) +
                        "&schl_name=" + encodeURIComponent(schlName) +
                        "&schl_address=" + encodeURIComponent(schlAddress);

                    window.location.href = url;
                });

                // Initialize Select2 for dropdowns inside modal
                $('#item_code, #item_code_med, #item_code_ppe').select2({
                    dropdownParent: $('#myModal')
                });
            });
        </script>


    </div>
</div>
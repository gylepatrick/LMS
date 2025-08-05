<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Online Public Access Catalog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .navbar-brand {
      font-weight: bold;
    }
    .sidebar {
      background-color: #ffffff;
      border-radius: 0.5rem;
      padding: 1rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .category-item.active {
      background-color: rgb(146, 146, 146);
      color: #fff;
    }
    .category-item {
      cursor: pointer;
    }
    .table thead th {
      vertical-align: middle;
    }
    .card-header h5 {
      margin: 0;
    }
    .alert {
      font-size: 0.875rem;
    }

    .btn-light {
      background-color: #e9ecef;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Online Public Access Catalog</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav"></div>
    </div>
  </nav>
  <div class="container-fluid my-3">
    <div class="bg-light shadow-sm rounded p-4">
      <div class="row align-items-center">
        <div class="col-md-2 text-center mb-3 mb-md-0">
          <img src="<?= $settings->school_logo ?>" alt="School Logo" class="img-fluid" style="max-height: 200px;">
        </div>
        <div class="col-md-10">
          <h5 class="fw-bold mb-1"><?= $settings->school_name ?></h5>
          <small class="text-muted d-block mb-2"><?= $settings->address ?></small>
          <h6 class="text-primary fw-semibold">Online Public Access Catalog (OPAC)</h6>
          <p class="mb-0">
            Welcome to the <?= $settings->school_name ?> Library OPAC! Search our collection of books, journals, and resources to assist you in your learning and research.
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid mt-4">
    <div class="row">
      <aside class="col-md-3 mb-3">
        <div class="sidebar">
          <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Search by title, author, or ISBN.
          </div>
          <div class="input-group mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Type to search...">
            <button class="btn btn-primary" id="searchBtn"><i class="fas fa-search"></i></button>
          </div>
          <div class="card">
            <div class="card-header bg-primary text-white py-2">
              <strong>Categories</strong>
            </div>
            <div class="card-body p-2">
              <ul class="ms-3 list-group list-group-flush" id="categoryList">
                <li class="list-group-item category-item active" data-category="All">All</li>
                <?php foreach ($subjects as $category): ?>
                  <li class="list-group-item category-item" data-category="<?= $category->subject ?>">
                    <?= $category->subject ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </aside>
      <main class="col-md-9">
        <div class="card shadow-sm mb-3 border-primary">
          <div class="card-header bg-primary text-white text-center py-2">
            <h5 class="mb-0">LIST OF BOOKS</h5>
          </div>
        </div>
        <div class="card shadow-sm mt-3">
          <div class="card-body">
            <div class="table-responsive">
              <table id="booksTable" class="table table-striped table-hover align-middle">
                <thead class="table-primary text-white">
                  <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Classification</th>
                    <th>Subject</th>
                    <th>Category</th>
                    <th>ISBN</th>
                    <th>Year</th>
                    <th>Language</th>
                  </tr>
                </thead>
                <tbody id="booksBody">
                  <?php foreach ($books as $book): ?>
                    <tr data-subject="<?= htmlspecialchars(strtolower(trim($book->subject))) ?>">
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
      </main>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      const table = $('#booksTable').DataTable({
        ordering: true,
        paging: true,
        searching: true
      });

      $("#searchInput").on("keyup", function() {
        table.search(this.value).draw();
      });

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
            const tableBody = $("#booksBody");
            tableBody.empty();

            if (response.length > 0) {
              response.forEach(book => {
                tableBody.append(`
                <tr data-subject="${book.subject.toLowerCase().trim()}">
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
              tableBody.append('<tr><td colspan="8" class="text-center text-danger">No books found</td></tr>');
            }

            table.clear().rows.add($("#booksBody tr")).draw();
          },
          error: function() {
            alert("Error fetching books.");
          }
        });
      });
      $('#booksTable').DataTable().destroy(); // before you filter

      // Filter by Subject
      const categoryItems = document.querySelectorAll('.category-item');
      categoryItems.forEach(item => {
        item.addEventListener('click', function() {
          categoryItems.forEach(i => i.classList.remove('active'));
          this.classList.add('active');

          const selectedCategory = this.getAttribute('data-category').toLowerCase().trim();
          const rows = document.querySelectorAll('#booksBody tr');

          rows.forEach(row => {
            const rowCategory = row.getAttribute('data-subject');
            if (selectedCategory === 'all' || rowCategory === selectedCategory) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        });
      });
    });
  </script>

</body>

</html>
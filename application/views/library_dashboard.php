<!-- Make sure Font Awesome is included -->
<div class="container my-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="display-4"><i class="fas fa-book-reader me-2"></i>Library Dashboard</h1>
      <p class="lead">Welcome to the library dashboard. Here you can manage your library resources, add books, and release books for borrowing.</p>
    </div>
  </div>

  <div class="row g-4">
    <!-- Resources -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-book me-2"></i>QUICK LINKS</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><i class="fas fa-book-open me-2 text-primary"></i><a href="<?php base_url('/books') ?>">Books</a></li>
            <li class="list-group-item"><i class="fas fa-newspaper me-2 text-secondary"></i><a href="#">Journals</a></li>
            <li class="list-group-item"><i class="fas fa-database me-2 text-info"></i><a href="#">Databases</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Statistics -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-chart-bar me-2"></i>Statistics</h5>
          <p class="mb-2"><i class="fas fa-book text-success me-2"></i>Total Books: <strong>1,200</strong></p>
          <p class="mb-0"><i class="fas fa-newspaper text-warning me-2"></i>Total Journals: <strong>300</strong></p>
        </div>
      </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-clock me-2"></i>Recent Activities</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><i class="fas fa-plus-circle text-success me-2"></i>New book added: <em>"Learning PHP"</em></li>
            <li class="list-group-item"><i class="fas fa-upload text-primary me-2"></i>Journal published: <em>"Tech Trends - March 2023"</em></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

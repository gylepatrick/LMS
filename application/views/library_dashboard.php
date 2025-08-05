<div class="container my-4">
 <div class="row mb-4">
  <div class="col">
    <div class="card shadow-sm border-0 bg-light">
      <div class="card-body py-4">
        <div class="d-flex align-items-center mb-2">
          <div>
            <h2 class="mb-0">Library Dashboard</h2>
            <small class="text-muted">Your central hub for managing all library resources</small>
          </div>
        </div>
        <p class="mb-0 text-secondary">
          View analytics, manage and monitor books.
        </p>
      </div>
    </div>
  </div>
</div>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-link me-2"></i> Quick Links</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <i class="fas fa-book-open me-2 text-primary"></i>
              <a href="<?= base_url('/opac/') ?>"> Online Public Access Catalog</a>
            </li>
            
          </ul>
        </div>
      </div>
    </div>

    <!-- Book Categories -->
    <div class="col-md-8">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-layer-group me-2"></i> Book Categories</h5>
          <ul class="list-group list-group-flush mb-3">
            <?php foreach ($book_categories as $category): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($category['category_subject']) ?>
                <span class="badge bg-primary text-white rounded-pill"><?= htmlspecialchars($category['count']) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <div class="text-end text-dark">
            <strong>Total Books: <?= htmlspecialchars($total_books) ?></strong>
          </div>
        </div>
      </div>
    </div>

    
  </div>
</div>

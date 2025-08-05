<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4">
            <h2 class="mb-3 fw-bold text-primary">
                <i class="fas fa-user-circle"></i> Welcome, <?= $this->session->userdata('full_name') ?>
            </h2>
            <h4 class="mb-4 text-secondary">
                <i class="fas fa-book"></i> Borrowed Books
            </h4>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th><i class="fas fa-user-edit"></i> Book Author</th>
                            <th><i class="fas fa-book-open"></i> Book Title</th>
                            <th><i class="fas fa-calendar-alt"></i> Borrow Date</th>
                            <th><i class="fas fa-calendar-check"></i> Return Date</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr class="text-center">
                                <td><?= htmlspecialchars($transaction->book_author) ?></td>
                                <td class="text-start"><?= htmlspecialchars($transaction->book_title) ?></td>
                                <td><?= htmlspecialchars($transaction->borrow_date) ?></td>
                                <td><?= htmlspecialchars($transaction->return_date) ?></td>
                                <td>
                                    <?php 
                                        $return_date = strtotime($transaction->return_date);
                                        $today = strtotime(date('Y-m-d'));

                                        if ($today > $return_date) {
                                            echo '<span class="badge bg-danger text-uppercase"><i class="fas fa-exclamation-triangle"></i> Overdue</span>';
                                        } elseif ($today >= ($return_date - (2 * 24 * 60 * 60)) && $transaction->actual_returned_date == NULL) {
                                            echo '<span class="badge bg-warning text-dark text-uppercase"><i class="fas fa-clock"></i> Due Soon</span>';
                                        } else if($transaction->actual_returned_date != NULL) {
                                            echo '<span class="badge bg-success text-uppercase"><i class="fas fa-check-circle"></i> On Time</span>';
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

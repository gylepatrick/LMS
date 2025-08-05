<div class="container mt-4">
    <h2 class="mb-4">School Configuration</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?= form_open_multipart('settings/update', ['class' => 'needs-validation', 'novalidate' => true]); ?>

    <div class="row">
        <!-- School Settings Column -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    School Settings
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="school_name" class="form-label">School Name</label>
                        <input type="text" class="form-control" id="school_name" name="school_name" value="<?= $settings->school_name; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?= $settings->address; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="school_logo" class="form-label">School Logo</label>
                        <input type="file" class="form-control" id="school_logo" name="school_logo">
                        <?php if ($settings->school_logo): ?>
                            <div class="mt-3">
                                <img src="<?= base_url($settings->school_logo); ?>" alt="Logo" class="img-thumbnail" style="height: 180px; max-width: 100%;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signatories Column -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">
                    Signatories
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="checked_by" class="form-label">Checked By</label>
                        <input type="text" name="checked_by" id="checked_by" class="form-control" value="<?= $settings->checked_by; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="approved_by" class="form-label">Approved By</label>
                        <input type="text" name="approved_by" id="approved_by" class="form-control" value="<?= $settings->approved_by; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="text-end">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-2"></i> Save Changes
    </button>
</div>


    </form>
</div>

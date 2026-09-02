<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <a href="index.php?action=client_dashboard" style="font-size:0.9rem; font-weight:600; color:var(--text-muted);">&larr; Back to Client Dashboard</a>
        <h1 style="font-size:1.75rem; font-weight:800; color:var(--text-main); margin-top:0.5rem;">Edit Job Listing</h1>
        <p style="color:var(--text-muted); font-size:0.95rem;">Update the scope, budget, or timeline for your job posting.</p>
    </div>

    <div class="card">
        <form action="index.php?action=edit_job" method="POST">
            <input type="hidden" name="id" value="<?= (int)$job['id'] ?>">

            <div class="form-group">
                <label class="form-label" for="jobTitle">Job Title <span style="color:var(--danger);">*</span></label>
                <input type="text" name="title" id="jobTitle" class="form-control" value="<?= htmlspecialchars($job['title']) ?>" required maxlength="150">
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="jobCategory">Category <span style="color:var(--danger);">*</span></label>
                    <select name="category" id="jobCategory" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= ($job['category'] === $cat) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="jobBudget">Budget (BDT ৳) <span style="color:var(--danger);">*</span></label>
                    <input type="number" name="budget" id="jobBudget" class="form-control" value="<?= (float)$job['budget'] ?>" min="100" step="100" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="jobDeadline">Project Deadline <span style="color:var(--danger);">*</span></label>
                <input type="date" name="deadline" id="jobDeadline" class="form-control" value="<?= htmlspecialchars($job['deadline']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="jobDescription">Detailed Scope & Requirements <span style="color:var(--danger);">*</span></label>
                <textarea name="description" id="jobDescription" class="form-control" rows="7" required minlength="20"><?= htmlspecialchars($job['description']) ?></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border);">
                <a href="index.php?action=client_dashboard" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

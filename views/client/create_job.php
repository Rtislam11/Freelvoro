<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <a href="index.php?action=client_dashboard" style="font-size:0.9rem; font-weight:600; color:var(--text-muted);">&larr; Back to Client Dashboard</a>
        <h1 style="font-size:1.75rem; font-weight:800; color:var(--text-main); margin-top:0.5rem;">Post a New Job</h1>
        <p style="color:var(--text-muted); font-size:0.95rem;">Describe your requirements to receive competitive bids from verified Bangladeshi professionals.</p>
    </div>

    <div class="card">
        <form action="index.php?action=create_job" method="POST">
            <div class="form-group">
                <label class="form-label" for="jobTitle">Job Title <span style="color:var(--danger);">*</span></label>
                <input type="text" name="title" id="jobTitle" class="form-control" placeholder="e.g. Build an E-Commerce Backend in Native PHP" required maxlength="150">
                <small style="color:var(--text-muted); font-size:0.8rem;">Write a clear, concise title explaining the main deliverable.</small>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="jobCategory">Category <span style="color:var(--danger);">*</span></label>
                    <select name="category" id="jobCategory" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="jobBudget">Budget (BDT ৳) <span style="color:var(--danger);">*</span></label>
                    <input type="number" name="budget" id="jobBudget" class="form-control" placeholder="e.g. 25000" min="100" step="100" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="jobDeadline">Project Deadline <span style="color:var(--danger);">*</span></label>
                <input type="date" name="deadline" id="jobDeadline" class="form-control" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                <small style="color:var(--text-muted); font-size:0.8rem;">Select a realistic target completion date for freelancers.</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="jobDescription">Detailed Scope & Requirements <span style="color:var(--danger);">*</span></label>
                <textarea name="description" id="jobDescription" class="form-control" rows="7" placeholder="Specify key features, required technical skills, milestones, and deliverables..." required minlength="20"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border);">
                <a href="index.php?action=client_dashboard" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Publish Job Listing</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.35rem;">
            <span class="role-tag client" style="font-size:0.75rem;">💼 Active Role: Client</span>
            <span style="font-size:0.8rem; color:var(--text-muted); background:white; border:1px solid var(--border); padding:0.15rem 0.5rem; border-radius:4px;">
                Permissions: Post Jobs &bull; Review Proposals &bull; Hire Freelancers &bull; File Disputes
            </span>
        </div>
        <h1 style="font-size:1.75rem; font-weight:800; color:var(--text-main);">Client Workspace</h1>
        <p style="color:var(--text-muted); font-size:0.95rem;">Manage your project listings, review freelancer proposals, and track ongoing contracts.</p>
    </div>
    <div>
        <a href="index.php?action=create_job" class="btn btn-primary">
            <span style="font-size:1.1rem; line-height:1;">+</span> Post a New Job
        </a>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon-wrapper green">📋</div>
        <div>
            <div class="stat-number"><?= (int)$totalJobs ?></div>
            <div class="stat-label">Total Jobs Posted</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrapper blue">⚡</div>
        <div>
            <div class="stat-number"><?= (int)$activeJobs ?></div>
            <div class="stat-label">Active Listings</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrapper indigo">📬</div>
        <div>
            <div class="stat-number"><?= (int)$totalBidsReceived ?></div>
            <div class="stat-label">Total Bids Received</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrapper amber">🤝</div>
        <div>
            <div class="stat-number"><?= (int)$activeHires ?></div>
            <div class="stat-label">Hired / In-Progress</div>
        </div>
    </div>
</div>

<!-- Client Jobs Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">My Job Postings</h2>
        <span style="font-size:0.85rem; color:var(--text-muted); font-weight:600;">Showing <?= count($jobs) ?> listings</span>
    </div>

    <?php if (empty($jobs)): ?>
        <div style="text-align:center; padding:3rem 1rem;">
            <div style="font-size:3rem; margin-bottom:1rem;">📂</div>
            <h3 style="font-size:1.2rem; font-weight:700; color:var(--text-main);">No jobs posted yet</h3>
            <p style="color:var(--text-muted); margin-bottom:1.5rem;">Start hiring verified Bangladeshi digital talent by creating your first job listing.</p>
            <a href="index.php?action=create_job" class="btn btn-primary">Post Your First Job</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Job Title & Category</th>
                        <th>Budget (BDT)</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Proposals</th>
                        <th>Contract Info</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td>
                                <strong style="font-size:1rem; color:var(--text-main); display:block;">
                                    <?= htmlspecialchars($job['title']) ?>
                                </strong>
                                <span style="font-size:0.8rem; color:var(--text-muted); background:var(--bg-subtle); padding:0.15rem 0.5rem; border-radius:4px; display:inline-block; margin-top:0.25rem;">
                                    <?= htmlspecialchars($job['category']) ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color:var(--primary); font-size:1rem;">৳ <?= number_format((float)$job['budget'], 2) ?></strong>
                            </td>
                            <td>
                                <span style="font-size:0.85rem; color:var(--text-muted);">
                                    <?= date('M d, Y', strtotime($job['deadline'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?= htmlspecialchars($job['status']) ?>">
                                    <?= htmlspecialchars(str_replace('_', ' ', $job['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <a href="index.php?action=view_bids&job_id=<?= (int)$job['id'] ?>" class="btn btn-secondary btn-sm" style="font-weight:700;">
                                    📬 <?= (int)$job['proposal_count'] ?> <?= ((int)$job['proposal_count'] === 1) ? 'Bid' : 'Bids' ?>
                                </a>
                            </td>
                            <td>
                                <?php if (!empty($job['hired_freelancer_name'])): ?>
                                    <span style="font-size:0.85rem; color:var(--primary); font-weight:600;">
                                        👤 <?= htmlspecialchars($job['hired_freelancer_name']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="font-size:0.85rem; color:var(--text-light);">None</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:right; white-space:nowrap;">
                                <a href="index.php?action=view_bids&job_id=<?= (int)$job['id'] ?>" class="btn btn-primary btn-sm" title="Review Proposals">
                                    Review Bids
                                </a>
                                
                                <?php if ($job['status'] === 'open'): ?>
                                    <a href="index.php?action=edit_job&id=<?= (int)$job['id'] ?>" class="btn btn-secondary btn-sm" title="Edit Job">
                                        Edit
                                    </a>
                                    <a href="index.php?action=close_job&id=<?= (int)$job['id'] ?>" class="btn btn-secondary btn-sm" onclick="return confirmAction('Are you sure you want to close this job listing?');" title="Close Listing">
                                        Close
                                    </a>
                                <?php endif; ?>

                                <a href="index.php?action=delete_job&id=<?= (int)$job['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmAction('Permanently delete this job and all received bids?');" title="Delete Job">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

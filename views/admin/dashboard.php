<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.35rem;">
            <span class="role-tag admin" style="font-size:0.75rem;">🔑 Active Role: Administrator</span>
            <span style="font-size:0.8rem; color:var(--text-muted); background:white; border:1px solid var(--border); padding:0.15rem 0.5rem; border-radius:4px;">
                Permissions: Platform Content Moderation &bull; Dispute Resolution &bull; User Account Management &bull; System Analytics
            </span>
        </div>
        <h1 style="font-size:1.75rem; font-weight:800; color:var(--text-main);">Administrator Control Center</h1>
        <p style="color:var(--text-muted); font-size:0.95rem;">Real-time system health, community analytics, and platform content moderation.</p>
    </div>
    <div style="display:flex; gap:0.75rem;">
        <a href="index.php?action=admin_reports" class="btn btn-secondary">
            ⚠️ Dispute Queue <?php if ($pendingReports > 0): ?><span class="badge badge-pending" style="margin-left:0.3rem;"><?= $pendingReports ?></span><?php endif; ?>
        </a>
        <a href="index.php?action=admin_users" class="btn btn-secondary">
            👥 User Management
        </a>
    </div>
</div>

<!-- Feature A4: System Analytics Overview KPI Cards -->
<div class="stats-grid">
    <!-- Total Users & Breakdown -->
    <div class="stat-card">
        <div class="stat-icon-wrapper blue">👥</div>
        <div>
            <div class="stat-number"><?= $userStats['total'] ?></div>
            <div class="stat-label">Total Users</div>
            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">
                <?= $userStats['clients'] ?> Clients &bull; <?= $userStats['freelancers'] ?> Freelancers &bull; <?= $userStats['admins'] ?> Admins
            </div>
        </div>
    </div>

    <!-- Active Job Postings -->
    <div class="stat-card">
        <div class="stat-icon-wrapper green">⚡</div>
        <div>
            <div class="stat-number"><?= $jobStats['active_jobs'] ?></div>
            <div class="stat-label">Active Job Postings</div>
            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">
                <?= $jobStats['open_jobs'] ?> Open &bull; <?= $jobStats['in_progress_jobs'] ?> In-Progress
            </div>
        </div>
    </div>

    <!-- Hires & Contracts -->
    <div class="stat-card">
        <div class="stat-icon-wrapper indigo">🤝</div>
        <div>
            <div class="stat-number"><?= $jobStats['total_hires'] ?></div>
            <div class="stat-label">Total Hired Projects</div>
            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">
                <?= $proposalStats['accepted'] ?> Accepted Proposals
            </div>
        </div>
    </div>

    <!-- Pending Dispute Queue -->
    <div class="stat-card">
        <div class="stat-icon-wrapper amber">🛡️</div>
        <div>
            <div class="stat-number" style="<?= ($pendingReports > 0) ? 'color:var(--danger);' : '' ?>"><?= $pendingReports ?></div>
            <div class="stat-label">Pending Disputes</div>
            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">
                <?= ($pendingReports > 0) ? 'Requires Administrator Action' : 'All Disputes Resolved' ?>
            </div>
        </div>
    </div>
</div>

<!-- Feature A1: Global Content Moderation Panel -->
<div class="card">
    <div class="card-header" style="flex-wrap:wrap; gap:1rem;">
        <div>
            <h2 class="card-title">🛡️ Platform Content Moderation (Job Listings)</h2>
            <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.2rem;">Oversee all active and historical project postings. Delete listings that violate terms of service.</p>
        </div>
        
        <!-- Status Filter Buttons -->
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="index.php?action=admin_dashboard" class="btn btn-sm <?= empty($statusFilter) ? 'btn-primary' : 'btn-secondary' ?>">All (<?= $jobStats['total_jobs'] ?>)</a>
            <a href="index.php?action=admin_dashboard&status=open" class="btn btn-sm <?= ($statusFilter === 'open') ? 'btn-primary' : 'btn-secondary' ?>">Open (<?= $jobStats['open_jobs'] ?>)</a>
            <a href="index.php?action=admin_dashboard&status=in_progress" class="btn btn-sm <?= ($statusFilter === 'in_progress') ? 'btn-primary' : 'btn-secondary' ?>">In Progress (<?= $jobStats['in_progress_jobs'] ?>)</a>
            <a href="index.php?action=admin_dashboard&status=closed" class="btn btn-sm <?= ($statusFilter === 'closed') ? 'btn-primary' : 'btn-secondary' ?>">Closed (<?= $jobStats['closed_jobs'] ?>)</a>
        </div>
    </div>

    <?php if (empty($jobs)): ?>
        <div style="text-align:center; padding:2.5rem 1rem; color:var(--text-muted);">
            No jobs found matching the current moderation filter.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Job ID & Title</th>
                        <th>Client Details</th>
                        <th>Category</th>
                        <th>Budget (BDT)</th>
                        <th>Proposals</th>
                        <th>Status</th>
                        <th>Posted Date</th>
                        <th style="text-align:right;">Moderation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $j): ?>
                        <tr>
                            <td>
                                <strong>#<?= (int)$j['id'] ?> <?= htmlspecialchars($j['title']) ?></strong>
                            </td>
                            <td>
                                <div style="font-weight:600;"><?= htmlspecialchars($j['client_name']) ?></div>
                                <div style="font-size:0.75rem; color:var(--text-muted);"><?= htmlspecialchars($j['client_email']) ?></div>
                            </td>
                            <td>
                                <span style="font-size:0.8rem; background:var(--bg-subtle); padding:0.2rem 0.5rem; border-radius:4px;">
                                    <?= htmlspecialchars($j['category']) ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color:var(--primary);">৳ <?= number_format((float)$j['budget'], 2) ?></strong>
                            </td>
                            <td>
                                <span style="font-weight:600;"><?= (int)$j['proposal_count'] ?></span>
                            </td>
                            <td>
                                <span class="badge badge-<?= htmlspecialchars($j['status']) ?>">
                                    <?= htmlspecialchars(str_replace('_', ' ', $j['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-size:0.8rem; color:var(--text-muted);"><?= date('M d, Y', strtotime($j['created_at'])) ?></span>
                            </td>
                            <td style="text-align:right;">
                                <form action="index.php?action=admin_delete_job" method="POST" style="display:inline;" onsubmit="return confirmAction('ADMIN WARNING: Are you sure you want to moderate and delete Job #<?= (int)$j['id'] ?>?\n\nThis will remove the job and all associated proposals immediately.');">
                                    <input type="hidden" name="job_id" value="<?= (int)$j['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Moderate & Delete Job">
                                        🗑️ Delete Listing
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

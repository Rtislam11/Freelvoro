<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <div style="margin-bottom:0.25rem;">
            <a href="index.php?action=admin_dashboard" style="font-size:0.9rem; font-weight:600; color:var(--text-muted);">&larr; Back to Admin Overview</a>
        </div>
        <h1 style="font-size:1.75rem; font-weight:800; color:var(--text-main);">Dispute & Moderation Queue</h1>
        <p style="color:var(--text-muted); font-size:0.95rem;">Process user-submitted reports, resolve disputes, and maintain platform integrity.</p>
    </div>

    <!-- Filter Pills -->
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <a href="index.php?action=admin_reports" class="btn btn-sm <?= empty($statusFilter) ? 'btn-primary' : 'btn-secondary' ?>">
            All Disputes (<?= count($reports) ?>)
        </a>
        <a href="index.php?action=admin_reports&status=pending" class="btn btn-sm <?= ($statusFilter === 'pending') ? 'btn-primary' : 'btn-secondary' ?>">
            ⚠️ Pending (<?= $pendingCount ?>)
        </a>
        <a href="index.php?action=admin_reports&status=resolved" class="btn btn-sm <?= ($statusFilter === 'resolved') ? 'btn-primary' : 'btn-secondary' ?>">
            ✓ Resolved
        </a>
        <a href="index.php?action=admin_reports&status=dismissed" class="btn btn-sm <?= ($statusFilter === 'dismissed') ? 'btn-primary' : 'btn-secondary' ?>">
            ✕ Dismissed
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Dispute Cases</h2>
        <span style="font-size:0.85rem; color:var(--text-muted);">Showing <?= count($reports) ?> records</span>
    </div>

    <?php if (empty($reports)): ?>
        <div style="text-align:center; padding:3rem 1rem;">
            <div style="font-size:3rem; margin-bottom:1rem;">🎉</div>
            <h3 style="font-size:1.2rem; font-weight:700; color:var(--text-main);">No reports found</h3>
            <p style="color:var(--text-muted);">There are no disputes or violation reports matching your filter criteria.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Case #</th>
                        <th>Reporter</th>
                        <th>Reported User</th>
                        <th>Job Context</th>
                        <th>Dispute Reason & Details</th>
                        <th>Status</th>
                        <th style="text-align:right;">Resolution Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $r): ?>
                        <tr style="<?= ($r['status'] === 'pending') ? 'background:#fffdfa;' : '' ?>">
                            <td>
                                <strong>#<?= (int)$r['id'] ?></strong>
                                <div style="font-size:0.75rem; color:var(--text-muted);"><?= date('M d, Y', strtotime($r['created_at'])) ?></div>
                            </td>
                            <td>
                                <div style="font-weight:600;"><?= htmlspecialchars($r['reporter_name']) ?></div>
                                <span class="role-tag <?= htmlspecialchars($r['reporter_role']) ?>" style="font-size:0.65rem;">
                                    <?= htmlspecialchars(ucfirst($r['reporter_role'])) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight:600; color:var(--danger);"><?= htmlspecialchars($r['reported_name']) ?></div>
                                <span class="role-tag <?= htmlspecialchars($r['reported_role']) ?>" style="font-size:0.65rem;">
                                    <?= htmlspecialchars(ucfirst($r['reported_role'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($r['job_title'])): ?>
                                    <span style="font-size:0.85rem; font-weight:600;">
                                        #<?= (int)$r['job_id'] ?>: <?= htmlspecialchars($r['job_title']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="font-size:0.8rem; color:var(--text-light);">General Flag</span>
                                <?php endif; ?>
                            </td>
                            <td style="max-width:320px;">
                                <strong style="color:var(--text-main); font-size:0.85rem; display:block; margin-bottom:0.25rem;">
                                    <?= htmlspecialchars($r['reason']) ?>
                                </strong>
                                <p style="font-size:0.825rem; color:var(--text-muted); line-height:1.4;">
                                    <?= nl2br(htmlspecialchars($r['details'])) ?>
                                </p>
                            </td>
                            <td>
                                <span class="badge badge-<?= htmlspecialchars($r['status']) ?>">
                                    <?= htmlspecialchars(ucfirst($r['status'])) ?>
                                </span>
                            </td>
                            <td style="text-align:right; white-space:nowrap;">
                                <?php if ($r['status'] === 'pending'): ?>
                                    <form action="index.php?action=resolve_report" method="POST" style="display:inline;" onsubmit="return confirmAction('Resolve Case #<?= (int)$r['id'] ?>? This acknowledges violation and marks dispute as resolved.');">
                                        <input type="hidden" name="report_id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-success btn-sm" style="font-weight:700;">
                                            ✓ Resolve
                                        </button>
                                    </form>

                                    <form action="index.php?action=dismiss_report" method="POST" style="display:inline; margin-left:0.25rem;" onsubmit="return confirmAction('Dismiss Case #<?= (int)$r['id'] ?> as invalid/inconclusive?');">
                                        <input type="hidden" name="report_id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-secondary btn-sm">
                                            ✕ Dismiss
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size:0.8rem; color:var(--text-muted); font-weight:600;">Case Closed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

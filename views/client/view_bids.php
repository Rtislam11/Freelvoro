<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="margin-bottom:1.5rem;">
    <a href="index.php?action=client_dashboard" style="font-size:0.9rem; font-weight:600; color:var(--text-muted);">&larr; Back to My Jobs</a>
</div>

<!-- Job Context Card -->
<div class="card" style="border-left: 5px solid var(--primary);">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
        <div>
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem;">
                <span class="badge badge-<?= htmlspecialchars($job['status']) ?>">
                    Job Status: <?= htmlspecialchars(str_replace('_', ' ', $job['status'])) ?>
                </span>
                <span style="font-size:0.85rem; color:var(--text-muted); background:var(--bg-subtle); padding:0.2rem 0.6rem; border-radius:4px;">
                    <?= htmlspecialchars($job['category']) ?>
                </span>
            </div>
            <h1 style="font-size:1.6rem; font-weight:800; color:var(--text-main); margin-bottom:0.5rem;">
                <?= htmlspecialchars($job['title']) ?>
            </h1>
            <p style="color:var(--text-muted); font-size:0.95rem; max-width:850px; line-height:1.6;">
                <?= nl2br(htmlspecialchars($job['description'])) ?>
            </p>
        </div>

        <div style="text-align:right; min-width:180px;">
            <div style="font-size:0.85rem; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Project Budget</div>
            <div style="font-size:1.75rem; font-weight:800; color:var(--primary);">
                ৳ <?= number_format((float)$job['budget'], 2) ?>
            </div>
            <div style="font-size:0.8rem; color:var(--text-light); margin-top:0.25rem;">
                Deadline: <?= date('M d, Y', strtotime($job['deadline'])) ?>
            </div>
        </div>
    </div>
</div>

<!-- Proposal Evaluation Section -->
<div style="margin-top:2.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <h2 style="font-size:1.35rem; font-weight:800; color:var(--text-main);">Submitted Proposals & Bids</h2>
            <p style="color:var(--text-muted); font-size:0.9rem;">Review cover notes, compare bids, and hire the top candidate.</p>
        </div>
        
    </div>

    <?php if (empty($proposals)): ?>
        <div class="card" style="text-align:center; padding:3rem 1rem;">
            <div style="font-size:3rem; margin-bottom:1rem;">⏳</div>
            <h3 style="font-size:1.2rem; font-weight:700; color:var(--text-main);">No bids submitted yet</h3>
            <p style="color:var(--text-muted); max-width:480px; margin:0 auto;">Freelancers on the marketplace are currently reviewing your job listing. You will be notified as soon as proposals arrive.</p>
        </div>
    <?php else: ?>
        <div class="proposals-grid">
            <?php foreach ($proposals as $prop): ?>
                <div class="proposal-card <?= ($prop['status'] === 'accepted') ? 'hired' : '' ?>">
                    <div>
                        <!-- Header & Freelancer Meta -->
                        <div class="proposal-header">
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <div class="freelancer-avatar">
                                    <?= strtoupper(substr($prop['freelancer_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div style="font-weight:700; color:var(--text-main); font-size:1.05rem;">
                                        <?= htmlspecialchars($prop['freelancer_name']) ?>
                                    </div>
                                    <div style="font-size:0.8rem; color:var(--text-muted);">
                                        <?= htmlspecialchars($prop['freelancer_email']) ?>
                                    </div>
                                </div>
                            </div>

                            <span class="badge badge-<?= htmlspecialchars($prop['status']) ?>">
                                <?= ($prop['status'] === 'accepted') ? '✓ Hired' : htmlspecialchars(ucfirst($prop['status'])) ?>
                            </span>
                        </div>

                        <!-- Bid Amount -->
                        <div style="display:flex; justify-content:space-between; align-items:center; background:var(--bg-subtle); padding:0.75rem 1rem; border-radius:var(--radius-md); margin-bottom:1rem;">
                            <span style="font-size:0.85rem; font-weight:600; color:var(--text-muted);">Offered Bid:</span>
                            <span class="bid-amount-tag">৳ <?= number_format((float)$prop['bid_amount'], 2) ?></span>
                        </div>

                        <!-- Pitch / Cover Note -->
                        <div style="margin-bottom:1.25rem;">
                            <div style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:0.4rem;">
                                Proposal Pitch:
                            </div>
                            <div style="font-size:0.925rem; color:var(--text-main); background:#ffffff; border:1px solid var(--border); border-radius:var(--radius-md); padding:0.85rem; line-height:1.5;">
                                <?= nl2br(htmlspecialchars($prop['proposal_text'])) ?>
                            </div>
                        </div>
                    </div>

                    <!-- Actions & Timestamp Footer -->
                    <div style="border-top:1px solid var(--border); padding-top:1rem; margin-top:0.5rem; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.78rem; color:var(--text-light);">
                            Submitted: <?= date('M d, Y h:i A', strtotime($prop['submitted_at'])) ?>
                        </span>

                        <div>
                            <?php if ($job['status'] === 'open' && $prop['status'] === 'pending'): ?>
                                <form action="index.php?action=hire_freelancer" method="POST" style="display:inline;" onsubmit="return confirmAction('Are you sure you want to hire <?= htmlspecialchars(addslashes($prop['freelancer_name'])) ?> for ৳<?= number_format((float)$prop['bid_amount'], 2) ?>?\n\nThis will mark the job as in-progress and auto-reject all other proposals.');">
                                    <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
                                    <input type="hidden" name="proposal_id" value="<?= (int)$prop['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm" style="font-weight:700;">
                                        🤝 Hire Freelancer
                                    </button>
                                </form>
                            <?php elseif ($prop['status'] === 'accepted'): ?>
                                <a href="index.php?action=file_report&user_id=<?= (int)$prop['freelancer_id'] ?>&job_id=<?= (int)$job['id'] ?>" class="btn btn-secondary btn-sm" style="color:var(--danger); border-color:#fecaca;" title="File Dispute Against Freelancer">
                                    ⚠️ Report Issue
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

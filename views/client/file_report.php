<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="max-width:750px; margin:0 auto;">
    <div style="margin-bottom:1.5rem;">
        <a href="index.php?action=client_dashboard" style="font-size:0.9rem; font-weight:600; color:var(--text-muted);">&larr; Back to Dashboard</a>
        <h1 style="font-size:1.75rem; font-weight:800; color:var(--text-main); margin-top:0.5rem;">Trust & Safety: File a Dispute</h1>
        <p style="color:var(--text-muted); font-size:0.95rem;">Submit an issue regarding contractual non-performance, communication breakdown, or platform policy violations.</p>
    </div>

    <div class="card" style="border-top: 4px solid var(--danger);">
        <form action="index.php?action=file_report" method="POST">
            <!-- Target User Selection -->
            <div class="form-group">
                <label class="form-label" for="reportedUserId">Offending User to Report <span style="color:var(--danger);">*</span></label>
                <select name="reported_user_id" id="reportedUserId" class="form-select" required>
                    <option value="">-- Select Member --</option>
                    <?php foreach ($candidates as $cand): ?>
                        <option value="<?= (int)$cand['id'] ?>" <?= ($targetUserId === (int)$cand['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cand['name']) ?> (<?= htmlspecialchars(ucfirst($cand['role'])) ?> &bull; <?= htmlspecialchars($cand['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <small style="color:var(--text-muted); font-size:0.8rem;">Select the freelancer or client profile involved in this dispute.</small>
            </div>

            <!-- Associated Job Selection -->
            <div class="form-group">
                <label class="form-label" for="reportJobId">Associated Job Listing (Optional)</label>
                <select name="job_id" id="reportJobId" class="form-select">
                    <option value="">-- None / General Platform Issue --</option>
                    <?php foreach ($clientJobs as $cj): ?>
                        <option value="<?= (int)$cj['id'] ?>" <?= ($jobId === (int)$cj['id']) ? 'selected' : '' ?>>
                            #<?= (int)$cj['id'] ?> - <?= htmlspecialchars($cj['title']) ?> (৳ <?= number_format((float)$cj['budget'], 2) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Reason Dropdown -->
            <div class="form-group">
                <label class="form-label" for="reportReason">Dispute Reason Code <span style="color:var(--danger);">*</span></label>
                <select name="reason" id="reportReason" class="form-select" required>
                    <option value="">-- Select Reason Code --</option>
                    <?php foreach ($reasons as $r): ?>
                        <option value="<?= htmlspecialchars($r) ?>"><?= htmlspecialchars($r) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Narrative / Evidence Textarea -->
            

            <div style="background:var(--warning-light); border:1px solid #fde68a; border-radius:var(--radius-md); padding:1rem; margin-top:1.5rem; display:flex; gap:0.75rem; align-items:flex-start;">
                <div style="font-size:1.2rem;">🛡️</div>
                <div style="font-size:0.85rem; color:#92400e;">
                    <strong>Dispute Procedure:</strong> Reports are prioritized by the platform moderation team. Both parties may be contacted for additional verification before administrative measures or dispute resolutions are concluded.
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border);">
                <a href="index.php?action=client_dashboard" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-danger" style="font-weight:700;">Submit Formal Dispute</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

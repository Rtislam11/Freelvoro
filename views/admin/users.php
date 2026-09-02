<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
    <div>
        <div style="margin-bottom:0.25rem;">
            <a href="index.php?action=admin_dashboard" style="font-size:0.9rem; font-weight:600; color:var(--text-muted);">&larr; Back to Admin Overview</a>
        </div>
        <h1 style="font-size:1.75rem; font-weight:800; color:var(--text-main);">User Account Management</h1>
        <p style="color:var(--text-muted); font-size:0.95rem;">Monitor platform registrations, provision new admin/client/freelancer accounts, and enforce community safety.</p>
    </div>

    <!-- Action & Role Filters -->
    <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
        <button type="button" class="btn btn-primary" onclick="openModal('createUserModal')">
            <span>+</span> Provision New Account
        </button>
    </div>
</div>

<!-- Filter Pills -->
<div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.5rem;">
    <a href="index.php?action=admin_users" class="btn btn-sm <?= empty($roleFilter) ? 'btn-primary' : 'btn-secondary' ?>">
        All Users (<?= $userStats['total'] ?>)
    </a>
    <a href="index.php?action=admin_users&role=client" class="btn btn-sm <?= ($roleFilter === 'client') ? 'btn-primary' : 'btn-secondary' ?>">
        💼 Clients (<?= $userStats['clients'] ?>)
    </a>
    <a href="index.php?action=admin_users&role=freelancer" class="btn btn-sm <?= ($roleFilter === 'freelancer') ? 'btn-primary' : 'btn-secondary' ?>">
        💻 Freelancers (<?= $userStats['freelancers'] ?>)
    </a>
    <a href="index.php?action=admin_users&role=admin" class="btn btn-sm <?= ($roleFilter === 'admin') ? 'btn-primary' : 'btn-secondary' ?>">
        🔑 Admins (<?= $userStats['admins'] ?>)
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">User Directory</h2>
        <span style="font-size:0.85rem; color:var(--text-muted);">Total Registered: <?= count($users) ?> accounts</span>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>User ID & Name</th>
                    <th>Email Address</th>
                    <th>Platform Role</th>
                    <th>Activity Stats</th>
                    <th>Flags Received</th>
                    <th>Registration Date</th>
                    <th style="text-align:right;">Account Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <strong style="color:var(--text-main); font-size:0.95rem;">
                                #<?= (int)$u['id'] ?> <?= htmlspecialchars($u['name']) ?>
                            </strong>
                            <?php if ((int)$u['id'] === (int)$currentUser['id']): ?>
                                <span style="font-size:0.7rem; background:#dbeafe; color:#1e40af; padding:0.1rem 0.4rem; border-radius:4px; font-weight:700; margin-left:0.25rem;">You</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="font-size:0.9rem; color:var(--text-muted);"><?= htmlspecialchars($u['email']) ?></span>
                        </td>
                        <td>
                            <span class="role-tag <?= htmlspecialchars($u['role']) ?>">
                                <?= htmlspecialchars(ucfirst($u['role'])) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($u['role'] === 'client'): ?>
                                <span style="font-size:0.85rem;">📋 <?= (int)$u['total_jobs'] ?> Jobs Posted</span>
                            <?php elseif ($u['role'] === 'freelancer'): ?>
                                <span style="font-size:0.85rem;">📬 <?= (int)$u['total_proposals'] ?> Proposals Sent</span>
                            <?php else: ?>
                                <span style="font-size:0.85rem; color:var(--text-light);">Admin Privileges</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ((int)$u['reports_against'] > 0): ?>
                                <span class="badge badge-rejected" style="color:var(--danger); background:var(--danger-light); font-weight:700;">
                                    ⚠️ <?= (int)$u['reports_against'] ?> <?= ((int)$u['reports_against'] === 1) ? 'Report' : 'Reports' ?>
                                </span>
                            <?php else: ?>
                                <span style="font-size:0.85rem; color:var(--success); font-weight:600;">✓ Clean Record</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="font-size:0.85rem; color:var(--text-muted);">
                                <?= date('M d, Y', strtotime($u['created_at'])) ?>
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <?php if ((int)$u['id'] !== (int)$currentUser['id']): ?>
                                <form action="index.php?action=terminate_user" method="POST" style="display:inline;" onsubmit="return confirmAction('ADMIN WARNING: Permanently terminate and delete User #<?= (int)$u['id'] ?> (<?= htmlspecialchars(addslashes($u['name'])) ?>)?\n\nAll associated jobs, proposals, and history will be wiped.');">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Terminate Account">
                                        🚫 Terminate Account
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="font-size:0.8rem; color:var(--text-light); font-style:italic;">Active Admin Session</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Admin User Provisioning Dialog -->
<div class="modal-backdrop" id="createUserModal">
    <div class="modal-box">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
            <h2 style="font-size:1.3rem; font-weight:800; color:var(--text-main);">Provision New Account</h2>
            <button type="button" class="toast-close" onclick="closeModal('createUserModal')">&times;</button>
        </div>
        <p style="color:var(--text-muted); font-size:0.875rem; margin-bottom:1.5rem;">
            Create a new verified Administrator, Client, or Freelancer account directly from the control panel.
        </p>

        <form action="index.php?action=admin_create_user" method="POST">
            <div class="form-group">
                <label class="form-label" for="newUserName">Full Name <span style="color:var(--danger);">*</span></label>
                <input type="text" name="name" id="newUserName" class="form-control" placeholder="e.g. Asif Mahmud" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="newUserEmail">Email Address <span style="color:var(--danger);">*</span></label>
                <input type="email" name="email" id="newUserEmail" class="form-control" placeholder="e.g. asif@marketplace.com" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="newUserRole">Account Role &amp; Permissions <span style="color:var(--danger);">*</span></label>
                <select name="role" id="newUserRole" class="form-select" required>
                    <option value="admin">🔑 Administrator (Full Moderation &amp; System Access)</option>
                    <option value="client">💼 Client (Job Poster &amp; Employer)</option>
                    <option value="freelancer">💻 Freelancer (Talent &amp; Bidding)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="newUserPassword">Initial Password <span style="color:var(--danger);">*</span></label>
                <input type="password" name="password" id="newUserPassword" class="form-control" placeholder="Min. 6 chars" required minlength="6">
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:2rem; padding-top:1rem; border-top:1px solid var(--border);">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createUserModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

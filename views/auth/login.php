<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <!-- Role-Wise Selector Tabs -->
        <div class="role-tab-group">
            <button type="button" id="tabBtn_client" class="role-tab-btn active client" onclick="selectLoginRole('client')">
                💼 Client
            </button>
            <button type="button" id="tabBtn_freelancer" class="role-tab-btn" onclick="selectLoginRole('freelancer')">
                💻 Freelancer
            </button>
            <button type="button" id="tabBtn_admin" class="role-tab-btn" onclick="selectLoginRole('admin')">
                🔑 Admin
            </button>
        </div>

        <div class="auth-header">
            <h1 class="auth-title" id="authRoleTitle">Client Sign In</h1>
            <p class="auth-subtitle" id="authRoleDesc">Hire top Bangladeshi talent and manage active contracts</p>
        </div>

        <form action="index.php?action=login" method="POST">
            <div class="form-group">
                <label class="form-label" for="loginEmail">Email Address</label>
                <input type="email" name="email" id="loginEmail" class="form-control" value="tanveer@client.com" placeholder="tanveer@client.com" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="loginPassword">Password</label>
                <input type="password" name="password" id="loginPassword" class="form-control" value="client123" placeholder="••••••••" required>
            </div>

            <div style="margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary" style="width:100%;">Sign In</button>
            </div>
        </form>

        <!-- Quick 1-Click Role Switcher Hint -->
        <div class="quick-demo-box">
            <div class="quick-demo-title">⚡ Instant Role Auto-Fill</div>
            <div class="demo-buttons">
                <button type="button" class="btn btn-secondary btn-sm" onclick="selectLoginRole('client')">💼 Client (Tanveer)</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="selectLoginRole('freelancer')">💻 Freelancer (Karim)</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="selectLoginRole('admin')">🔑 Admin (System)</button>
            </div>
        </div>

        <div style="text-align:center; margin-top:1.5rem; font-size:0.9rem; color:var(--text-muted);">
            Don't have an account yet? 
            <a href="index.php?action=register" style="font-weight:700;">Create Account</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

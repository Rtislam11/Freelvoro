<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="auth-container">
    <div class="auth-card" style="max-width:560px;">
        <div class="auth-header">
            <div style="font-size:2.5rem; margin-bottom:0.5rem;">🚀</div>
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Join the premier Bangladeshi peer-to-peer freelancing community</p>
        </div>

        <form action="index.php?action=register" method="POST">
            <!-- Visual Role Selection Cards -->
            <label class="form-label" style="margin-bottom:0.6rem;">Choose Your Role:</label>
            <div class="role-cards-grid">
                <label class="role-card-option selected" id="card_client" onclick="selectRegisterRole('client')">
                    <input type="radio" name="role" id="roleRadio_client" value="client" checked>
                    <div style="font-size:1.8rem; margin-bottom:0.35rem;">💼</div>
                    <strong style="color:var(--text-main); font-size:0.95rem; display:block;">I Want to Hire (Client)</strong>
                    <span style="font-size:0.75rem; color:var(--text-muted); line-height:1.3; display:block; margin-top:0.25rem;">
                        Post jobs, review bids &amp; hire verified talent
                    </span>
                </label>

                <label class="role-card-option" id="card_freelancer" onclick="selectRegisterRole('freelancer')">
                    <input type="radio" name="role" id="roleRadio_freelancer" value="freelancer">
                    <div style="font-size:1.8rem; margin-bottom:0.35rem;">💻</div>
                    <strong style="color:var(--text-main); font-size:0.95rem; display:block;">I Want to Work (Freelancer)</strong>
                    <span style="font-size:0.75rem; color:var(--text-muted); line-height:1.3; display:block; margin-top:0.25rem;">
                        Submit proposals &amp; earn on projects
                    </span>
                </label>
            </div>

            <!-- Dynamic Client-Specific Options -->
            <div id="clientOptionsSection" style="background:var(--primary-light); border:1px solid #a7f3d0; border-radius:var(--radius-md); padding:1rem; margin-bottom:1.25rem;">
                <div style="font-size:0.85rem; font-weight:700; color:var(--primary); margin-bottom:0.75rem; display:flex; align-items:center; gap:0.4rem;">
                    💼 Client Preferences &amp; Project Focus
                </div>
                <div class="form-grid-2">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="font-size:0.8rem;" for="clientCompany">Company / Employer Name</label>
                        <input type="text" name="company_name" id="clientCompany" class="form-control" placeholder="e.g. Dhaka Tech Ltd. or Individual">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="font-size:0.8rem;" for="clientInterest">Primary Hiring Category</label>
                        <select name="hiring_interest" id="clientInterest" class="form-select">
                            <option value="Web Development">Web Development</option>
                            <option value="UI/UX Design">UI/UX &amp; Figma Design</option>
                            <option value="Mobile App Development">Mobile App Development</option>
                            <option value="Digital Marketing">Digital Marketing &amp; SEO</option>
                            <option value="Data & AI">Data Science &amp; AI</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Dynamic Freelancer-Specific Options -->
            <div id="freelancerOptionsSection" style="display:none; background:var(--secondary-light); border:1px solid #c7d2fe; border-radius:var(--radius-md); padding:1rem; margin-bottom:1.25rem;">
                <div style="font-size:0.85rem; font-weight:700; color:var(--secondary); margin-bottom:0.75rem; display:flex; align-items:center; gap:0.4rem;">
                    💻 Freelancer Professional Profile
                </div>
                <div class="form-grid-2">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="font-size:0.8rem;" for="freelancerTitle">Professional Title / Skill</label>
                        <input type="text" name="professional_title" id="freelancerTitle" class="form-control" placeholder="e.g. Full Stack PHP Developer">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="font-size:0.8rem;" for="freelancerExp">Experience Level</label>
                        <select name="experience_level" id="freelancerExp" class="form-select">
                            <option value="Entry Level (1-2 years)">Entry Level (1-2 years)</option>
                            <option value="Intermediate (3-5 years)">Intermediate (3-5 years)</option>
                            <option value="Senior / Lead (5+ years)">Senior / Lead (5+ years)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Core User Info Fields -->
            <div class="form-group">
                <label class="form-label" for="regName">Full Name <span style="color:var(--danger);">*</span></label>
                <input type="text" name="name" id="regName" class="form-control" placeholder="e.g. Tanveer Ahmed" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="regEmail">Email Address <span style="color:var(--danger);">*</span></label>
                <input type="email" name="email" id="regEmail" class="form-control" placeholder="name@example.com" required>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label" for="regPassword">Password <span style="color:var(--danger);">*</span></label>
                    <input type="password" name="password" id="regPassword" class="form-control" placeholder="Min. 6 chars" required minlength="6">
                </div>
                <div class="form-group">
                    <label class="form-label" for="regConfirmPassword">Confirm Password <span style="color:var(--danger);">*</span></label>
                    <input type="password" name="confirm_password" id="regConfirmPassword" class="form-control" placeholder="Repeat password" required minlength="6">
                </div>
            </div>

            <div style="margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary" style="width:100%;">Create My Account</button>
            </div>
        </form>

        <div style="text-align:center; margin-top:1.5rem; font-size:0.9rem; color:var(--text-muted);">
            Already have an account? 
            <a href="index.php?action=login" style="font-weight:700;">Sign In</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

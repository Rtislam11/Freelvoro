/**
 * Integrated Freelance Marketplace
 * Core Client-side Scripts & Interactive Behaviors
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Auto-dismiss flash alerts after 6 seconds
    const alerts = document.querySelectorAll('.toast-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }, 6000);
    });

    // Close button for alerts
    document.querySelectorAll('.toast-close').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const toast = e.target.closest('.toast-alert');
            if (toast) toast.remove();
        });
    });

    // 2. Role-Wise Login Tab Switcher & Autofill
    window.selectLoginRole = function(role) {
        // Update tab button styles
        document.querySelectorAll('.role-tab-btn').forEach(btn => {
            btn.classList.remove('active', 'client', 'freelancer', 'admin');
        });
        const activeBtn = document.getElementById('tabBtn_' + role);
        if (activeBtn) {
            activeBtn.classList.add('active', role);
        }

        // Update role header & subtitle
        const titleEl = document.getElementById('authRoleTitle');
        const descEl = document.getElementById('authRoleDesc');
        const emailInput = document.getElementById('loginEmail');
        const passInput = document.getElementById('loginPassword');

        if (role === 'client') {
            if (titleEl) titleEl.textContent = 'Client Sign In';
            if (descEl) descEl.textContent = 'Hire top Bangladeshi talent and manage active contracts';
            if (emailInput) {
                emailInput.value = 'tanveer@client.com';
                emailInput.placeholder = 'tanveer@client.com';
            }
            if (passInput) passInput.value = 'client123';
        } else if (role === 'freelancer') {
            if (titleEl) titleEl.textContent = 'Freelancer Sign In';
            if (descEl) descEl.textContent = 'Review job listings and submit competitive proposals';
            if (emailInput) {
                emailInput.value = 'karim@dev.com';
                emailInput.placeholder = 'karim@dev.com';
            }
            if (passInput) passInput.value = 'freelancer123';
        } else if (role === 'admin') {
            if (titleEl) titleEl.textContent = 'Administrator Access';
            if (descEl) descEl.textContent = 'Platform content moderation, dispute queue & user admin';
            if (emailInput) {
                emailInput.value = 'admin@marketplace.com';
                emailInput.placeholder = 'admin@marketplace.com';
            }
            if (passInput) passInput.value = 'admin123';
        }
    };

    window.fillDemoCredentials = function(role) {
        window.selectLoginRole(role);
    };

    // 3. Role-Wise Registration Card Selector & Dynamic Field Toggler
    window.selectRegisterRole = function(role) {
        document.querySelectorAll('.role-card-option').forEach(card => card.classList.remove('selected'));
        const activeCard = document.getElementById('card_' + role);
        const radio = document.getElementById('roleRadio_' + role);
        if (activeCard) activeCard.classList.add('selected');
        if (radio) radio.checked = true;

        // Dynamically toggle role-specific option sections
        const clientSec = document.getElementById('clientOptionsSection');
        const freelancerSec = document.getElementById('freelancerOptionsSection');

        if (role === 'client') {
            if (clientSec) clientSec.style.display = 'block';
            if (freelancerSec) freelancerSec.style.display = 'none';
        } else if (role === 'freelancer') {
            if (clientSec) clientSec.style.display = 'none';
            if (freelancerSec) freelancerSec.style.display = 'block';
        }
    };

    // 3. Modal Dialog Helpers
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    // Close modal when clicking on backdrop
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                backdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // 4. Confirmation dialog helper
    window.confirmAction = function(message) {
        return confirm(message || 'Are you sure you want to proceed with this action?');
    };
});

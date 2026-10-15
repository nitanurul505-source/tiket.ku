<?php ob_start(); ?>

<div style="max-width:560px; margin:0 auto;">
    <div style="text-align:center; margin-bottom:32px;">
        <h1 style="font-family:'Playfair Display',serif; font-size:2.2rem; font-weight:800; color:var(--charcoal);">Create an Account</h1>
        <p style="color:var(--muted); font-size:0.9rem; margin-top:8px;">Sign up to start booking event tickets.</p>
    </div>

    <?php if(isset($_GET['error']) && $_GET['error'] == 'email_exists'): ?>
    <div class="alert-error" style="margin-bottom:20px;">
        <strong>Email already registered.</strong> Use a different email address or contact the admin.
    </div>
    <?php endif; ?>

    <div style="background:white; border-radius:16px; border:1px solid var(--stone-warm); overflow:hidden;">
        <!-- Top stripe -->
        <div style="height:6px; background:linear-gradient(90deg, var(--terracotta), var(--amber-soft), var(--forest));"></div>

        <div style="padding:36px 40px;">
            <form action="?controller=user&action=register" method="POST" style="display:flex; flex-direction:column; gap:20px;">

                <div>
                    <label class="form-label">full Name</label>
                    <input type="text" name="name" required class="form-input" placeholder="Budi Santoso">
                </div>

                <div>
                    <label class="form-label">Email Adderess</label>
                    <input type="email" name="email" required class="form-input" placeholder="budi@email.com">
                </div>

                <div>
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" required class="form-input" placeholder="081234567890">
                </div>

                <div style="padding-top:8px; border-top:1px solid #F0EAE0;">
                    <button type="submit" class="btn-terra" style="width:100%; justify-content:center; padding:0.75rem; font-size:0.95rem;">
                        Sign Up Now →
                    </button>
                </div>

                <p style="text-align:center; font-size:0.82rem; color:var(--muted);">
                    Already have a ticket? <a href="?controller=ticket&action=index" style="color:var(--terracotta); font-weight:600; text-decoration:none;">Check ticket status →</a>
                </p>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/main.php';
?>

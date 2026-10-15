<?php ob_start(); ?>
<div style="max-width:440px; margin:40px auto;">
    <div style="text-align:center; margin-bottom:24px;">
        <a href="/event_ticketing/public/index.php" style="font-family:'Playfair Display',serif; font-weight:900; font-size:2rem; color:var(--terracotta); text-decoration:none;">tiket<span style="color:var(--charcoal);">ku</span></a>
        <p style="color:var(--muted); font-size:0.88rem; margin-top:6px;">Create an account to start booking tickets</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert-error" style="margin-bottom:16px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div style="background:white; border-radius:16px; border:1px solid var(--stone-warm); overflow:hidden;">
        <div style="height:4px; background:linear-gradient(90deg,var(--terracotta),var(--amber-soft),var(--forest));"></div>
        <div style="padding:32px;">
            <form method="POST" action="?controller=auth&action=register" style="display:flex;flex-direction:column;gap:16px;">
                <div>
                    <label class="form-label">full Name</label>
                    <input type="text" name="name" required class="form-input" placeholder="Budi Santoso">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" required class="form-input" placeholder="budi@email.com">
                </div>
                <div>
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" required class="form-input" placeholder="081234567890">
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" required class="form-input" placeholder="Buat password">
                </div>
                <button type="submit" class="btn-terra" style="justify-content:center;padding:0.7rem;font-size:0.9rem;margin-top:4px;">
                    Create an account →
                </button>
            </form>
            <p style="text-align:center;margin-top:20px;font-size:0.82rem;color:var(--muted);">
                Do you already have an account? <a href="?controller=auth&action=login" style="color:var(--terracotta);font-weight:600;text-decoration:none;">Login</a>
            </p>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
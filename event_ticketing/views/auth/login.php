<?php ob_start(); ?>
<div style="max-width:420px; margin:48px auto;">
    <div style="text-align:center; margin-bottom:28px;">
        <a href="/event_ticketing/public/index.php" style="font-family:'Playfair Display',serif; font-weight:900; font-size:2rem; color:var(--terracotta); text-decoration:none;">tiket<span style="color:var(--charcoal);">ku</span></a>
        <p style="color:var(--muted); font-size:0.88rem; margin-top:6px;">Sign in to view &amp; purchase tickets</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert-error" style="margin-bottom:16px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div style="background:white; border-radius:16px; border:1px solid var(--stone-warm); overflow:hidden;">
        <div style="height:4px; background:linear-gradient(90deg,var(--terracotta),var(--amber-soft),var(--forest));"></div>
        <div style="padding:32px;">
            <form method="POST" action="?controller=auth&action=login" style="display:flex;flex-direction:column;gap:18px;">
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" required class="form-input" placeholder="nama@email.com" autofocus>
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" required class="form-input" placeholder="••••••••">
                </div>
                <button type="submit" class="btn-terra" style="justify-content:center;padding:0.7rem;font-size:0.9rem;margin-top:4px;">
                    Login →
                </button>
            </form>
            <p style="text-align:center;margin-top:20px;font-size:0.82rem;color:var(--muted);">
                Don’t have an account yet? <a href="?controller=auth&action=register" style="color:var(--terracotta);font-weight:600;text-decoration:none;">Sign up now</a>
            </p>
        </div>
    </div>

    <div style="background:white;border:1px solid var(--stone-warm);border-radius:10px;padding:14px 16px;margin-top:16px;">
        <p style="font-size:0.73rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;">Demo account</p>
        <div style="display:flex;flex-direction:column;gap:4px;font-size:0.8rem;color:var(--ink);">
            <span>👤 <strong>budi@example.com</strong> / budi123</span>
            <span>👤 <strong>siti@example.com</strong> / siti123</span>
            <span>⚙ <strong>admin@tiketku.com</strong> / admin123</span>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
<?php ob_start(); ?>
<div>
    <div style="margin-bottom:28px;">
        <h1 style="font-size:1.9rem; font-weight:700; color:var(--charcoal);">My Ticket</h1>
        <p style="color:var(--muted); font-size:0.85rem; margin-top:3px;">
            Halo, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong> — <?= count($tickets) ?> ticket found
        </p>
    </div>

    <?php if (empty($tickets)): ?>
    <div style="text-align:center; padding:64px 20px; background:white; border-radius:16px; border:1px solid var(--stone-warm);">
        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="var(--stone-warm)" stroke-width="1.5" style="margin:0 auto 16px;"><path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z"/></svg>
        <p style="color:var(--muted); font-size:0.95rem; margin-bottom:20px;">You don't have a ticket yet.</p>
        <a href="/event_ticketing/public/index.php?controller=event&action=index" class="btn-terra">Explore Events →</a>
    </div>
    <?php else: ?>
    <div style="display:flex; flex-direction:column; gap:14px;">
        <?php foreach ($tickets as $t):
            $statusColor = [
                'pending'   => ['bg'=>'#FEF3C7','text'=>'#92400E','dot'=>'#F59E0B'],
                'confirmed' => ['bg'=>'#D1FAE5','text'=>'#065F46','dot'=>'#10B981'],
                'cancelled' => ['bg'=>'#FEE2E2','text'=>'#991B1B','dot'=>'#EF4444'],
            ][$t['status']] ?? ['bg'=>'#F3F4F6','text'=>'#374151','dot'=>'#9CA3AF'];
        ?>
        <div class="ticket-card">
            <div style="display:flex; align-items:stretch; flex-wrap:wrap;">
                <!-- Left color strip -->
                <div style="width:6px; background:var(--terracotta); flex-shrink:0; border-radius:0;"></div>

                <!-- Main info -->
                <div style="flex:1; padding:18px 20px; min-width:200px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:8px; margin-bottom:10px;">
                        <div>
                            <h3 style="font-size:1.05rem; font-weight:700; color:var(--charcoal);"><?= htmlspecialchars($t['event_name']) ?></h3>
                            <p style="font-size:0.78rem; color:var(--muted); margin-top:2px;"><?= htmlspecialchars($t['organizer_name'] ?? '') ?></p>
                        </div>
                        <span style="background:<?= $statusColor['bg'] ?>;color:<?= $statusColor['text'] ?>;font-size:0.67rem;font-weight:700;padding:3px 10px;border-radius:999px;letter-spacing:0.06em;text-transform:uppercase;white-space:nowrap;">
                            <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:<?= $statusColor['dot'] ?>;margin-right:4px;vertical-align:middle;"></span>
                            <?= strtoupper($t['status']) ?>
                        </span>
                    </div>

                    <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:12px;">
                        <div style="display:flex;align-items:center;gap:5px;color:var(--muted);font-size:0.8rem;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <?= date('d M Y, H:i', strtotime($t['event_date'])) ?> WIB
                        </div>
                        <div style="display:flex;align-items:center;gap:5px;color:var(--muted);font-size:0.8rem;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?= htmlspecialchars($t['venue'] ?? '') ?>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; padding-top:10px; border-top:1px solid #F0EAE0;">
                        <div>
                            <p style="font-size:0.66rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; color:var(--muted);">Ticket Code</p>
                            <p style="font-family:monospace; font-weight:700; color:var(--terracotta); font-size:0.88rem; letter-spacing:0.04em;"><?= htmlspecialchars($t['ticket_code']) ?></p>
                        </div>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <span style="font-family:'Playfair Display',serif;font-weight:700;color:var(--forest);font-size:1rem;">Rp <?= number_format($t['price'] * ($t['qty'] ?? 1), 0, ',', '.') ?></span>
                            <a href="/event_ticketing/public/index.php?controller=ticket&action=detail&id=<?= $t['id'] ?>" class="btn-terra" style="padding:5px 14px;font-size:0.78rem;">
                                View Ticket →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
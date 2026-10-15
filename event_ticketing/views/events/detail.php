<?php ob_start();
$img = !empty($event->image_url) ? $event->image_url
     : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=900&q=80';
$tab = $_GET['tab'] ?? 'ringkasan';
?>

<!-- Breadcrumb -->
<div style="display:flex;align-items:center;gap:6px;margin-bottom:20px;font-size:0.78rem;color:var(--muted);">
    <a href="?controller=event&action=index" style="color:var(--blue-mid);text-decoration:none;font-weight:600;">Home</a>
    <span>›</span>
    <span><?= htmlspecialchars($event->name) ?></span>
</div>

<!-- TOP SECTION: Posters + Info (like tiket.com) -->
<div style="display:flex;gap:28px;align-items:flex-start;flex-wrap:wrap;margin-bottom:32px;">

    <!-- Poster -->
    <div style="flex:0 0 auto;width:340px;max-width:100%;">
        <div style="border-radius:14px;overflow:hidden;box-shadow:0 8px 32px rgba(15,23,42,0.15);">
            <img src="<?= htmlspecialchars($img) ?>"
                 alt="<?= htmlspecialchars($event->name) ?>"
                 style="width:100%;display:block;object-fit:cover;max-height:380px;"
                 onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=70'">
        </div>
    </div>

    <!-- Info on the right -->
    <div style="flex:1;min-width:260px;">
        <p style="font-size:0.72rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--blue-mid);margin-bottom:6px;"><?= htmlspecialchars($event->organizer_name ?? '') ?></p>
        <h1 style="font-size:1.9rem;font-weight:800;color:var(--ink);line-height:1.2;margin-bottom:18px;"><?= htmlspecialchars($event->name) ?></h1>

        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px;">
            <div style="display:flex;align-items:flex-start;gap:10px;">
                <div style="width:34px;height:34px;border-radius:8px;background:var(--blue-pale);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--blue-mid)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <p style="font-size:0.72rem;color:var(--muted);font-weight:600;">Location</p>
                    <p style="font-size:0.88rem;color:var(--ink);font-weight:600;"><?= htmlspecialchars($event->venue) ?></p>
                    <?php if (!empty($event->location_detail)): ?>
                    <p style="font-size:0.78rem;color:var(--muted);"><?= htmlspecialchars($event->location_detail) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display:flex;align-items:flex-start;gap:10px;">
                <div style="width:34px;height:34px;border-radius:8px;background:var(--blue-pale);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--blue-mid)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div>
                    <p style="font-size:0.72rem;color:var(--muted);font-weight:600;">Date & Time</p>
                    <p style="font-size:0.88rem;color:var(--ink);font-weight:600;"><?= date('l, d F Y', strtotime($event->event_date)) ?></p>
                    <p style="font-size:0.78rem;color:var(--muted);"><?= date('H:i', strtotime($event->event_date)) ?> WIB</p>
                </div>
            </div>

            <div style="display:flex;align-items:flex-start;gap:10px;">
                <div style="width:34px;height:34px;border-radius:8px;background:var(--blue-pale);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--blue-mid)" stroke-width="2"><path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z"/></svg>
                </div>
                <div>
                    <p style="font-size:0.72rem;color:var(--muted);font-weight:600;">Ticket prices start at</p>
                    <p style="font-size:1.15rem;font-weight:800;color:var(--blue-mid);">Rp <?= number_format($event->price,0,',','.') ?></p>
                </div>
            </div>
        </div>

        <!-- CTA Box -->
        <div style="background:var(--blue-xpale);border:1.5px solid var(--blue-pale);border-radius:12px;padding:16px 18px;">
            <?php
            $soldFromTickets = $event->getConfirmedTicketsCount();
            $totalSold  = $soldFromTickets;
            $remaining  = $event->capacity - $soldFromTickets;
            ?>
            <p style="font-size:0.82rem;color:var(--blue-deep);font-weight:600;margin-bottom:10px;">
                <?= $remaining > 0 ? "✅ Tickets are available—buy them before they sell out!" : "🚫 Tickets are sold out!" ?>
            </p>
            <?php if ($remaining <= 0): ?>
                <button disabled style="width:100%;padding:10px;background:#CBD5E1;color:#94A3B8;border:none;border-radius:8px;font-weight:700;font-size:0.9rem;cursor:not-allowed;">
                    🚫 Tickets are sold out
                </button>
            <?php elseif (isset($_SESSION['user_id'])): ?>
                <a href="?controller=ticket&action=create&event_id=<?= $event->id ?>" class="btn-primary" style="width:100%;justify-content:center;padding:10px;">
                    Buy tickets now
                </a>
            <?php else: ?>
                <a href="?controller=auth&action=login" class="btn-primary" style="width:100%;justify-content:center;padding:10px;">
                    Sign In to Buy Tickets
                </a>
            <?php endif; ?>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <div style="display:flex;gap:8px;margin-top:8px;">
                <a href="?controller=event&action=edit&id=<?= $event->id ?>" class="btn-ghost" style="flex:1;justify-content:center;font-size:0.78rem;padding:6px;">Edit</a>
                <a href="?controller=event&action=delete&id=<?= $event->id ?>" class="btn-ghost" style="flex:1;justify-content:center;font-size:0.78rem;padding:6px;color:var(--danger);border-color:#FECACA;" onclick="return confirm('Delete?')">Delete</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- TAB SECTION -->
<div style="background:#fff;border-radius:14px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--shadow-card);">

    <!-- Tab header -->
    <div style="padding:0 24px;border-bottom:2px solid var(--border);">
        <div style="display:flex;gap:0;">
            <?php foreach (['ringkasan'=>'Summary','kategori'=>'Category &amp; Price','detail'=>'Detail Event'] as $key => $label): ?>
            <button onclick="switchTab('<?= $key ?>')"
                id="tab-btn-<?= $key ?>"
                style="padding:14px 20px;font-size:0.85rem;font-weight:700;border:none;background:transparent;cursor:pointer;border-bottom:2.5px solid transparent;margin-bottom:-2px;color:var(--muted);transition:all 0.18s;white-space:nowrap;<?= $tab===$key?'color:var(--blue-mid);border-bottom-color:var(--blue-mid);':'' ?>">
                <?= $label ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tab content -->
    <div style="padding:28px;">

        <!-- Ringkasan -->
        <div id="panel-ringkasan" style="display:<?= $tab==='ringkasan'?'block':'none' ?>;">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--ink);margin-bottom:14px;">About the Event</h2>
            <?php if (!empty($event->description)): ?>
            <p style="color:var(--ink-soft);font-size:0.9rem;line-height:1.75;"><?= nl2br(htmlspecialchars($event->description)) ?></p>
            <?php else: ?>
            <p style="color:var(--muted);font-size:0.88rem;">Event description not yet available.</p>
            <?php endif; ?>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
                <div style="background:var(--blue-xpale);border-radius:10px;padding:14px;">
                    <p style="font-size:0.68rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:4px;">Capacity</p>
                    <p style="font-size:1.1rem;font-weight:800;color:var(--ink);"><?= number_format($event->capacity) ?> <span style="font-size:0.78rem;font-weight:500;color:var(--muted);">People</span></p>
                </div>
                <div style="background:var(--blue-xpale);border-radius:10px;padding:14px;">
                    <p style="font-size:0.68rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:4px;">Sold</p>
                    <p style="font-size:1.1rem;font-weight:800;color:var(--ink);"><?= $totalSold ?> <span style="font-size:0.78rem;font-weight:500;color:var(--muted);">ticket</span></p>
                </div>
                <div style="background:var(--blue-xpale);border-radius:10px;padding:14px;">
                    <p style="font-size:0.68rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);margin-bottom:4px;">Remaining</p>
                    <p style="font-size:1.1rem;font-weight:800;color:<?= $remaining>0?'var(--blue-mid)':'var(--danger)' ?>;"><?= $remaining ?> <span style="font-size:0.78rem;font-weight:500;color:var(--muted);">ticket</span></p>
                </div>
            </div>
        </div>

        <!-- Kategori & Harga -->
        <div id="panel-kategori" style="display:<?= $tab==='kategori'?'block':'none' ?>;">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--ink);margin-bottom:16px;">Category &amp; Ticket prices</h2>
            <?php if (empty($categories)): ?>
            <p style="color:var(--muted);">No ticket categories yet.</p>
            <?php else: ?>
            <div style="border:1px solid var(--border);border-radius:10px;overflow:hidden;">
                <div style="display:grid;grid-template-columns:1fr auto auto;background:#F1F5FF;padding:10px 16px;font-size:0.7rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--muted);gap:16px;">
                    <span>Category</span><span style="text-align:right;">Price</span><span style="text-align:right;">Remaining</span>
                </div>
                <?php foreach ($categories as $cat):
                    $sisa = $cat['quota'] - $cat['sold'];
                    $habis = $sisa <= 0;
                ?>
                <div style="display:grid;grid-template-columns:1fr auto auto;padding:14px 16px;border-top:1px solid var(--border);align-items:center;gap:16px;<?= $habis?'opacity:0.5;':'' ?>">
                    <div>
                        <p style="font-weight:700;color:var(--ink);font-size:0.9rem;"><?= htmlspecialchars($cat['name']) ?></p>
                        <?php if ($habis): ?><span style="font-size:0.68rem;background:#FEE2E2;color:#991B1B;padding:1px 7px;border-radius:3px;font-weight:700;">Sold out</span><?php endif; ?>
                    </div>
                    <p style="font-weight:800;color:var(--blue-mid);font-size:0.95rem;white-space:nowrap;text-align:right;">Rp <?= number_format($cat['price'],0,',','.') ?></p>
                    <p style="font-size:0.82rem;color:var(--muted);text-align:right;"><?= $sisa ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <p style="font-size:0.76rem;color:var(--muted);margin-top:10px;">ⓘ Service fees and taxes apply at checkout.</p>
            <?php endif; ?>
        </div>

        <!-- Detail Event -->
        <div id="panel-detail" style="display:<?= $tab==='detail'?'block':'none' ?>;">
            <h2 style="font-size:1.2rem;font-weight:800;color:var(--ink);margin-bottom:16px;">Detail Event</h2>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <?php $details = [
                    ['icon'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>','label'=>'Hari & Tanggal','val'=>date('l, d F Y', strtotime($event->event_date))],
                    ['icon'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>','label'=>'Waktu','val'=>date('H:i', strtotime($event->event_date)) . ' WIB'],
                    ['icon'=>'<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>','label'=>'Venue','val'=>$event->venue],
                    ['icon'=>'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>','label'=>'Penyelenggara','val'=>$event->organizer_name ?? '-'],
                    ['icon'=>'<path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z"/>','label'=>'Harga Mulai','val'=>'Rp ' . number_format($event->price,0,',','.')],
                    ['icon'=>'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>','label'=>'Kapasitas','val'=>number_format($event->capacity) . ' orang'],
                ]; ?>
                <?php foreach ($details as $d): ?>
                <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 14px;background:var(--bg);border-radius:10px;">
                    <div style="width:32px;height:32px;border-radius:7px;background:var(--blue-pale);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--blue-mid)" stroke-width="2"><?= $d['icon'] ?></svg>
                    </div>
                    <div>
                        <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:var(--muted);"><?= $d['label'] ?></p>
                        <p style="font-size:0.9rem;font-weight:600;color:var(--ink);margin-top:1px;"><?= htmlspecialchars($d['val']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<script>
function switchTab(key) {
    ['ringkasan','kategori','detail'].forEach(k => {
        document.getElementById('panel-' + k).style.display = k === key ? 'block' : 'none';
        const btn = document.getElementById('tab-btn-' + k);
        btn.style.color = k === key ? 'var(--blue-mid)' : 'var(--muted)';
        btn.style.borderBottomColor = k === key ? 'var(--blue-mid)' : 'transparent';
    });
}
</script>

<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
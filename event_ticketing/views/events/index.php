<?php ob_start(); ?>
<div>
    <!-- HERO + SEARCH -->
    <div class="hero-bg" style="border-radius:16px;padding:40px 44px;margin-bottom:32px;position:relative;">
        <div style="position:relative;z-index:1;max-width:580px;">
            <p style="font-size:0.72rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:rgba(255,255,255,0.6);margin-bottom:8px;">🎫 Campus Ticket Platform</p>
            <h1 style="font-size:2.4rem;font-weight:800;color:#fff;line-height:1.15;margin-bottom:6px;">Find Events<br><span style="color:#93C5FD;">Your favorite</span></h1>
            <p style="color:rgba(255,255,255,0.65);font-size:0.9rem;margin-bottom:24px;">Seminars, concerts, workshops — book tickets in minutes.</p>

            <form method="GET" action="/event_ticketing/public/index.php" style="max-width:480px;">
                <input type="hidden" name="controller" value="event">
                <input type="hidden" name="action"     value="index">
                <div class="search-bar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.6)" stroke-width="2" style="margin-left:14px;flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" placeholder="Search for events, venues, and organizers..."
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </form>
        </div>

        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <div style="position:absolute;bottom:24px;right:32px;z-index:1;">
            <a href="?controller=event&action=create" class="btn-white" style="font-size:0.8rem;padding:7px 16px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add an event
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- HASIL SEARCH -->
    <?php if (!empty($_GET['search'])): ?>
    <p style="font-size:0.85rem;color:var(--muted);margin-bottom:16px;">
        Showing results for <strong style="color:var(--ink);">"<?= htmlspecialchars($_GET['search']) ?>"</strong>
        — <?= count($events) ?> event found &nbsp;·&nbsp;
        <a href="?controller=event&action=index" style="color:var(--blue-mid);font-weight:600;text-decoration:none;">Clear filters ×</a>
    </p>
    <?php else: ?>
    <p style="font-size:0.85rem;font-weight:600;color:var(--ink-soft);margin-bottom:16px;">All Events <span style="color:var(--muted);font-weight:400;">(<?= count($events) ?>)</span></p>
    <?php endif; ?>

    <?php if (empty($events)): ?>
    <div style="text-align:center;padding:64px 20px;background:#fff;border-radius:14px;border:1px solid var(--border);">
        <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="1.5" style="margin:0 auto 14px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <p style="color:var(--muted);margin-bottom:16px;">No events were found.</p>
        <a href="?controller=event&action=index" class="btn-primary" style="font-size:0.82rem;">View all events</a>
    </div>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">
        <?php foreach ($events as $ev):
            $img = !empty($ev['image_url']) ? $ev['image_url']
                 : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=70';
        ?>
        <div class="event-card">
            <!-- Gambar -->
            <div class="event-card-banner">
                <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($ev['name']) ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=70'">
                <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,0.55) 0%,transparent 55%);"></div>
                <div style="position:absolute;top:10px;left:10px;">
                    <span style="background:rgba(255,255,255,0.18);backdrop-filter:blur(6px);color:#fff;font-size:0.65rem;font-weight:700;padding:3px 9px;border-radius:4px;border:1px solid rgba(255,255,255,0.2);">
                        <?= htmlspecialchars($ev['organizer_name'] ?? '') ?>
                    </span>
                </div>
                <div style="position:absolute;bottom:10px;left:12px;right:12px;">
                    <p style="font-size:1rem;font-weight:800;color:#fff;line-height:1.25;text-shadow:0 1px 4px rgba(0,0,0,0.4);">
                        <?= htmlspecialchars($ev['name']) ?>
                    </p>
                </div>
            </div>

            <!-- Info -->
            <div style="padding:14px 16px;flex:1;display:flex;flex-direction:column;gap:6px;">
                <p style="font-size:0.75rem;color:var(--muted);display:flex;align-items:center;gap:5px;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?= date('d M Y · H:i', strtotime($ev['event_date'])) ?> WIB
                </p>
                <p style="font-size:0.75rem;color:var(--muted);display:flex;align-items:center;gap:5px;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?= htmlspecialchars($ev['venue']) ?>
                </p>

                <div style="margin-top:auto;padding-top:10px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <p style="font-size:0.6rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--muted);">Start from</p>
                        <p style="font-size:1rem;font-weight:800;color:var(--blue-mid);">Rp <?= number_format($ev['price'],0,',','.') ?></p>
                    </div>
                    <a href="?controller=event&action=detail&id=<?= $ev['id'] ?>" class="btn-primary" style="font-size:0.78rem;padding:6px 14px;">
                        See →
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
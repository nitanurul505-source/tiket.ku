<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiketku — Campus Event Ticket Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --white:        #FFFFFF;
            --bg:           #F0F4FF;
            --bg-card:      #FFFFFF;
            --border:       #E2E8F4;
            --blue-deep:    #1B3A8C;
            --blue-mid:     #2563EB;
            --blue-soft:    #3B82F6;
            --blue-light:   #60A5FA;
            --blue-pale:    #DBEAFE;
            --blue-xpale:   #EFF6FF;
            --cyan:         #0EA5E9;
            --ink:          #0F172A;
            --ink-soft:     #334155;
            --muted:        #64748B;
            --muted-light:  #94A3B8;
            --success:      #10B981;
            --warning:      #F59E0B;
            --danger:       #EF4444;
            --grad-main:    linear-gradient(135deg, #1B3A8C 0%, #2563EB 60%, #0EA5E9 100%);
            --grad-card:    linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            --shadow-blue:  0 4px 20px rgba(37,99,235,0.18);
            --shadow-card:  0 2px 16px rgba(15,23,42,0.07);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: var(--grad-main);
            position: sticky; top: 0; z-index: 50;
            box-shadow: 0 2px 16px rgba(27,58,140,0.25);
        }
        .nav-inner {
            max-width: 1200px; margin: 0 auto;
            padding: 0 24px; height: 58px;
            display: flex; align-items: center;
            justify-content: space-between; gap: 16px;
        }
        .nav-logo {
            font-weight: 800; font-size: 1.35rem;
            color: #FFFFFF; text-decoration: none; letter-spacing: -0.03em;
        }
        .nav-logo .dot { color: #93C5FD; }
        .nav-links { display: flex; align-items: center; gap: 2px; }
        .nav-link {
            color: rgba(255,255,255,0.75);
            font-size: 0.8rem; font-weight: 600;
            letter-spacing: 0.03em;
            padding: 6px 11px; border-radius: 6px;
            text-decoration: none; transition: all 0.15s;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.15);
        }
        .nav-sep { width: 1px; height: 18px; background: rgba(255,255,255,0.2); margin: 0 6px; }
        .nav-badge {
            background: rgba(255,255,255,0.22);
            color: #fff;
            font-size: 0.58rem; font-weight: 800;
            padding: 1px 5px; border-radius: 3px;
            letter-spacing: 0.04em; text-transform: uppercase;
            vertical-align: middle; margin-left: 3px;
        }
        .nav-user { display: flex; align-items: center; gap: 8px; }
        .nav-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: rgba(255,255,255,0.25);
            border: 2px solid rgba(255,255,255,0.4);
            color: #fff; font-weight: 800; font-size: 0.75rem;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .nav-username { color: rgba(255,255,255,0.85); font-size: 0.8rem; font-weight: 600; }
        .nav-logout {
            color: rgba(255,255,255,0.6); font-size: 0.75rem;
            text-decoration: none; padding: 4px 9px; border-radius: 5px;
            border: 1px solid rgba(255,255,255,0.2); transition: all 0.15s;
        }
        .nav-logout:hover { color: #FCA5A5; background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); }

        /* ── BUTTONS ── */
        .btn-primary {
            background: var(--blue-mid);
            color: #fff; padding: 0.55rem 1.3rem;
            border-radius: 8px; font-weight: 700; font-size: 0.84rem;
            border: none; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.18s; letter-spacing: 0.01em;
        }
        .btn-primary:hover { background: var(--blue-deep); transform: translateY(-1px); box-shadow: var(--shadow-blue); }
        .btn-white {
            background: #fff; color: var(--blue-mid);
            padding: 0.55rem 1.3rem; border-radius: 8px;
            font-weight: 700; font-size: 0.84rem;
            border: none; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px; transition: all 0.18s;
        }
        .btn-white:hover { background: var(--blue-xpale); transform: translateY(-1px); }
        .btn-ghost {
            background: transparent; color: var(--muted);
            padding: 0.55rem 1.3rem; border-radius: 8px;
            font-weight: 600; font-size: 0.84rem;
            border: 1.5px solid var(--border); text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px; transition: all 0.18s;
        }
        .btn-ghost:hover { background: var(--bg); color: var(--ink); }
        /* compat */
        .btn-terra  { background: var(--blue-mid); color: #fff; padding: 0.55rem 1.3rem; border-radius: 8px; font-weight: 700; font-size: 0.84rem; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.18s; }
        .btn-terra:hover  { background: var(--blue-deep); transform: translateY(-1px); box-shadow: var(--shadow-blue); }
        .btn-forest { background: var(--success); color: #fff; padding: 0.55rem 1.3rem; border-radius: 8px; font-weight: 600; font-size: 0.84rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.18s; }
        .btn-forest:hover { filter: brightness(1.07); transform: translateY(-1px); }

        /* ── FORM ── */
        .form-input {
            width: 100%; padding: 0.6rem 0.9rem;
            border: 1.5px solid var(--border); border-radius: 8px;
            background: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem; color: var(--ink);
            outline: none; transition: border-color 0.18s, box-shadow 0.18s;
        }
        .form-input:focus { border-color: var(--blue-soft); box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
        .form-label { display: block; font-size: 0.73rem; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase; color: var(--muted); margin-bottom: 5px; }

        /* ── BADGES ── */
        .badge { display: inline-flex; align-items: center; padding: 2px 9px; border-radius: 999px; font-size: 0.67rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; }
        .badge-pending   { background: #FEF3C7; color: #92400E; }
        .badge-confirmed { background: #DBEAFE; color: #1E40AF; }
        .badge-cancelled { background: #FEE2E2; color: #991B1B; }
        .badge-paid      { background: #D1FAE5; color: #065F46; }
        .badge-unpaid    { background: #FEF3C7; color: #92400E; }
        .badge-failed    { background: #FEE2E2; color: #991B1B; }

        /* ── TABLE ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th { background: #F1F5FF; padding: 11px 15px; text-align: left; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.09em; text-transform: uppercase; color: var(--muted); }
        .data-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
        .data-table tbody tr:hover { background: var(--blue-xpale); }
        .data-table tbody td { padding: 13px 15px; font-size: 0.86rem; }

        /* ── ALERTS ── */
        .alert-success { background: #D1FAE5; border-left: 4px solid var(--success); color: #065F46; padding: 11px 15px; border-radius: 8px; font-size: 0.84rem; font-weight: 500; }
        .alert-error   { background: #FEE2E2; border-left: 4px solid var(--danger);  color: #991B1B; padding: 11px 15px; border-radius: 8px; font-size: 0.84rem; }
        .alert-info    { background: var(--blue-pale); border-left: 4px solid var(--blue-soft); color: #1E40AF; padding: 11px 15px; border-radius: 8px; font-size: 0.84rem; }

        /* ── HERO / FORM SIDEBAR ── */
        .hero-bg {
            background: var(--grad-main);
            position: relative; overflow: hidden;
        }
        .hero-bg::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse at 85% 15%, rgba(96,165,250,0.35) 0%, transparent 50%),
                radial-gradient(ellipse at 10% 85%, rgba(14,165,233,0.2)  0%, transparent 50%);
            pointer-events: none;
        }
        .form-sidebar {
            background: var(--grad-main);
            position: relative; overflow: hidden;
        }
        .form-sidebar::after {
            content: '';
            position: absolute; bottom: -50px; left: -50px;
            width: 200px; height: 200px; border-radius: 50%;
            border: 35px solid rgba(255,255,255,0.07);
            pointer-events: none;
        }

        /* ── EVENT CARD ── */
        .event-card {
            background: #fff; border-radius: 14px;
            overflow: hidden; border: 1px solid var(--border);
            transition: all 0.25s cubic-bezier(0.2,0,0,1);
            display: flex; flex-direction: column;
            box-shadow: var(--shadow-card);
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 36px rgba(37,99,235,0.14);
            border-color: rgba(59,130,246,0.3);
        }
        .event-card-banner { height: 180px; position: relative; overflow: hidden; }
        .event-card-banner img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .event-card:hover .event-card-banner img { transform: scale(1.04); }

        /* ── TICKET CARD ── */
        .ticket-card { background: #fff; border-radius: 14px; border: 1px solid var(--border); overflow: hidden; transition: all 0.2s; box-shadow: var(--shadow-card); }
        .ticket-card:hover { box-shadow: 0 8px 28px rgba(37,99,235,0.1); transform: translateY(-2px); border-color: rgba(59,130,246,0.25); }

        /* ── ID PREVIEW ── */
        .id-preview { font-family: 'Courier New', monospace; font-size: 0.95rem; font-weight: 700; color: var(--blue-mid); background: var(--blue-pale); border: 1.5px dashed var(--blue-soft); border-radius: 8px; padding: 9px 14px; letter-spacing: 0.08em; }

        /* ── SEARCH ── */
        .search-bar {
            display: flex; align-items: center;
            background: rgba(255,255,255,0.15);
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 12px; overflow: hidden;
            backdrop-filter: blur(8px);
            transition: all 0.2s;
        }
        .search-bar:focus-within {
            background: rgba(255,255,255,0.22);
            border-color: rgba(255,255,255,0.5);
            box-shadow: 0 0 0 3px rgba(255,255,255,0.1);
        }
        .search-bar input {
            flex: 1; background: transparent; border: none; outline: none;
            padding: 12px 16px; color: #fff; font-size: 0.95rem;
            font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 500;
        }
        .search-bar input::placeholder { color: rgba(255,255,255,0.55); }
        .search-bar button {
            background: rgba(255,255,255,0.2); border: none; cursor: pointer;
            padding: 12px 18px; color: #fff; transition: background 0.15s;
            display: flex; align-items: center;
        }
        .search-bar button:hover { background: rgba(255,255,255,0.3); }

        /* ── TAB ── */
        .tab-list { display: flex; border-bottom: 2px solid var(--border); gap: 0; }
        .tab-btn {
            padding: 12px 20px; font-size: 0.85rem; font-weight: 600;
            color: var(--muted); border: none; background: transparent;
            cursor: pointer; border-bottom: 2px solid transparent;
            margin-bottom: -2px; transition: all 0.18s; white-space: nowrap;
        }
        .tab-btn:hover { color: var(--blue-mid); }
        .tab-btn.active { color: var(--blue-mid); border-bottom-color: var(--blue-mid); }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* ── MISC ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
        .page-fade { animation: fadeUp 0.3s ease both; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        main { flex: 1 0 auto; }
        footer { flex-shrink: 0; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        <a href="/event_ticketing/public/index.php" class="nav-logo">tiket<span class="dot">.</span>ku</a>

        <div class="nav-links">
            <?php
            $ctrl   = $_GET['controller'] ?? 'event';
            $action = $_GET['action']     ?? 'index';
            ?>

            <a href="/event_ticketing/public/index.php?controller=event&action=index"
            class="nav-link <?= ($ctrl === 'event' && in_array($action, ['index','detail'])) ? 'active' : '' ?>">Home</a>

            <?php if (isset($_SESSION['user_id']) && empty($_SESSION['is_admin'])): ?>
                <a href="/event_ticketing/public/index.php?controller=ticket&action=index"
                class="nav-link <?= ($ctrl === 'ticket') ? 'active' : '' ?>">My Ticket</a>
            <?php endif; ?>

            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <div class="nav-sep"></div>
                <a href="/event_ticketing/public/index.php?controller=event&action=create"
                class="nav-link <?= ($ctrl === 'event' && in_array($action, ['create','edit'])) ? 'active' : '' ?>">+ Event <span class="nav-badge">Admin</span></a>
                <a href="/event_ticketing/public/index.php?controller=user&action=index"
                class="nav-link <?= ($ctrl === 'user') ? 'active' : '' ?>">Users <span class="nav-badge">Admin</span></a>
                <a href="/event_ticketing/public/index.php?controller=payment&action=index"
                class="nav-link <?= ($ctrl === 'payment') ? 'active' : '' ?>">Payment <span class="nav-badge">Admin</span></a>
            <?php endif; ?>
        </div>

        <div class="nav-user">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="nav-avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?></div>
                <span class="nav-username"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
                <a href="/event_ticketing/public/index.php?controller=auth&action=logout" class="nav-logout">Log out</a>
            <?php else: ?>
                <a href="/event_ticketing/public/index.php?controller=auth&action=login"  class="nav-link <?= ($ctrl === 'auth' && $action === 'login') ? 'active' : '' ?>">Login</a>
                <a href="/event_ticketing/public/index.php?controller=auth&action=register" class="nav-link <?= ($ctrl === 'auth' && $action === 'register') ? 'active' : '' ?>">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if (isset($_GET['success'])): ?>
<div style="max-width:1200px;margin:0 auto;padding:14px 24px 0;">
    <?php $msgs=['login'=>'Welcome Back!','registered'=>'The account was successfully created!','added'=>'The event was successfully added!','updated'=>'The event was successfully updated!','deleted'=>'The event was successfully deleted.','cancelled'=>'The ticket was successfully canceled.','confirmed'=>'Verified payment!','rejected'=>'Payment declined.','user_deleted'=>'User deleted.']; ?>
    <div class="alert-success">✓ <?= htmlspecialchars($msgs[$_GET['success']] ?? 'The operation was successful.') ?></div>
</div>
<?php endif; ?>

<main>
    <div style="max-width:1200px;margin:0 auto;padding:28px 24px;" class="page-fade">
        <?= $content ?? '' ?>
    </div>
</main>

<footer style="padding:28px 24px;border-top:1px solid var(--border);background:#fff;margin-top:48px;">
    <div style="max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <div>
            <span style="font-weight:800;font-size:1rem;color:var(--blue-mid);">tiket<span style="color:var(--blue-light);">.</span>ku</span>
            <p style="font-size:0.73rem;color:var(--muted);margin-top:1px;">Campus Event Ticket Platform — Web Application Development</p>
        </div>
        <p style="font-size:0.73rem;color:var(--muted);">© 2026 Tiketku</p>
    </div>
</footer>
</body>
</html>
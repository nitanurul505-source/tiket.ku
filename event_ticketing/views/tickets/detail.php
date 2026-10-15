<?php ob_start();
$qrData = json_encode([
    'code'  => $ticket->ticket_code,
    'event' => $ticket->event_name,
    'name'  => $ticket->user_name,
    'date'  => $ticket->event_date,
    'venue' => $ticket->venue,
]);
?>
<!-- QR Code library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<div style="max-width:760px; margin:0 auto;">
    <!-- Breadcrumb -->
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:0.79rem;color:var(--muted);">
        <a href="?controller=ticket&action=index" style="color:var(--terracotta);text-decoration:none;font-weight:600;">← My Ticket</a>
        <span>›</span>
        <span><?= htmlspecialchars($ticket->ticket_code) ?></span>
    </div>

    <?php
    $isConfirmed = $ticket->status === 'confirmed';
    $isPending   = $ticket->status === 'pending';
    $isCancelled = $ticket->status === 'cancelled';
    ?>

    <!-- TICKET CARD (mirip tiket fisik) -->
    <div style="background:white; border-radius:16px; border:1px solid var(--stone-warm); overflow:hidden; box-shadow:0 8px 32px rgba(44,36,22,0.08);">

        <!-- TOP: dark header dengan info event -->
        <div class="hero-pattern" style="padding:32px 36px; position:relative; overflow:hidden;">
            <div style="position:absolute;right:-40px;top:-40px;width:200px;height:200px;border-radius:50%;background:rgba(196,98,45,0.1);pointer-events:none;"></div>
            <div style="position:absolute;left:200px;bottom:-50px;width:160px;height:160px;border-radius:50%;border:28px solid rgba(196,98,45,0.08);pointer-events:none;"></div>

            <div style="position:relative;z-index:1;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;">
                <div>
                    <p style="font-size:0.67rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:var(--terracotta-light);margin-bottom:8px;"><?= htmlspecialchars($ticket->organizer_name ?? '') ?></p>
                    <h1 style="font-size:1.9rem;font-weight:700;color:var(--ink);line-height:1.2;margin-bottom:12px;"><?= htmlspecialchars($ticket->event_name) ?></h1>
                    <div style="display:flex;flex-wrap:wrap;gap:16px;">
                        <div style="display:flex;align-items:center;gap:6px;color:#A89880;font-size:0.83rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <?= date('d M Y', strtotime($ticket->event_date)) ?>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;color:#A89880;font-size:0.83rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?= date('H:i', strtotime($ticket->event_date)) ?> WIB
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;color:#A89880;font-size:0.83rem;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?= htmlspecialchars($ticket->venue ?? '') ?>
                        </div>
                    </div>
                </div>
                <?php
                $sc = ['pending'=>['#FEF3C7','#92400E'],'confirmed'=>['#D1FAE5','#065F46'],'cancelled'=>['#FEE2E2','#991B1B']][$ticket->status] ?? ['#F3F4F6','#374151'];
                ?>
                <span style="background:<?= $sc[0] ?>;color:<?= $sc[1] ?>;font-size:0.72rem;font-weight:700;padding:6px 14px;border-radius:6px;letter-spacing:0.06em;text-transform:uppercase;align-self:flex-start;">
                    <?= strtoupper($ticket->status) ?>
                </span>
            </div>
        </div>

        <!-- Dashed tear line -->
        <div style="border-top:2px dashed #E0D8CC;margin:0 20px;position:relative;">
            <div style="position:absolute;left:-28px;top:-12px;width:24px;height:24px;border-radius:50%;background:var(--cream);border:1px solid var(--stone-warm);"></div>
            <div style="position:absolute;right:-28px;top:-12px;width:24px;height:24px;border-radius:50%;background:var(--cream);border:1px solid var(--stone-warm);"></div>
        </div>

        <!-- BOTTOM: detail + QR -->
        <div style="padding:28px 36px;">
            <div style="display:flex;gap:32px;flex-wrap:wrap;align-items:flex-start;">

                <!-- Left: detail pemesan & info tiket -->
                <div style="flex:1;min-width:200px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                        <div>
                            <p style="font-size:0.66rem;font-weight:700;letter-spacing:0.09em;text-transform:uppercase;color:var(--muted);margin-bottom:3px;">Order</p>
                            <p style="font-weight:600;color:var(--charcoal);font-size:0.9rem;"><?= htmlspecialchars($ticket->user_name) ?></p>
                        </div>
                        <div>
                            <p style="font-size:0.66rem;font-weight:700;letter-spacing:0.09em;text-transform:uppercase;color:var(--muted);margin-bottom:3px;">Number of Tickets</p>
                            <p style="font-weight:800;color:var(--blue-mid);font-size:1rem;"><?= $ticket->qty ?? 1 ?> <span style="font-size:0.78rem;font-weight:500;color:var(--muted);">ticket</span></p>
                        </div>
                        <div>
                            <p style="font-size:0.66rem;font-weight:700;letter-spacing:0.09em;text-transform:uppercase;color:var(--muted);margin-bottom:3px;">Price per Ticket</p>
                            <p style="font-weight:600;color:var(--ink);font-size:0.88rem;">Rp <?= number_format($ticket->price, 0, ',', '.') ?></p>
                        </div>
                        <div>
                            <p style="font-size:0.66rem;font-weight:700;letter-spacing:0.09em;text-transform:uppercase;color:var(--muted);margin-bottom:3px;">Total Payment</p>
                            <p style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;color:var(--blue-mid);font-size:1.05rem;">Rp <?= number_format($ticket->price * ($ticket->qty ?? 1), 0, ',', '.') ?></p>
                        </div>
                        <div>
                            <p style="font-size:0.66rem;font-weight:700;letter-spacing:0.09em;text-transform:uppercase;color:var(--muted);margin-bottom:3px;">Ticket Code</p>
                            <p style="font-family:monospace;font-weight:700;color:var(--blue-mid);font-size:0.88rem;letter-spacing:0.04em;"><?= htmlspecialchars($ticket->ticket_code) ?></p>
                        </div>
                        <div>
                            <p style="font-size:0.66rem;font-weight:700;letter-spacing:0.09em;text-transform:uppercase;color:var(--muted);margin-bottom:3px;">Purchase Date</p>
                            <p style="font-weight:500;color:var(--ink);font-size:0.85rem;"><?= date('d M Y, H:i', strtotime($ticket->purchase_date ?? 'now')) ?></p>
                        </div>
                    </div>

                    <?php if ($isPending): ?>
                    <div class="alert-info" style="margin-bottom:16px;">
                        <strong>Waiting for verification.</strong> The admin will confirm your payment shortly.
                    </div>
                    <?php elseif ($isCancelled): ?>
                    <div class="alert-error" style="margin-bottom:16px;">
                        This ticket has been canceled.
                    </div>
                    <?php endif; ?>

                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a href="?controller=ticket&action=index" class="btn-ghost" style="font-size:0.8rem;padding:6px 14px;">← All Ticket</a>
                        <?php if ($isPending): ?>
                        <a href="?controller=ticket&action=cancel&id=<?= $ticket->id ?>"
                           class="btn-ghost"
                           style="font-size:0.8rem;padding:6px 14px;color:#DC2626;border-color:#FECACA;"
                           onclick="return confirm('Batalkan tiket ini?');">Cancel Ticket</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right: QR Code -->
                <div style="display:flex;flex-direction:column;align-items:center;gap:10px;flex-shrink:0;">
                    <?php if ($isConfirmed): ?>
                    <div style="background:white;border:2px solid var(--stone-warm);border-radius:12px;padding:14px;">
                        <div id="qrcode"></div>
                    </div>
                    <p style="font-size:0.7rem;color:var(--muted);text-align:center;max-width:140px;line-height:1.4;">Scan this QR code to access the event</p>
                    <?php else: ?>
                    <div style="width:148px;height:148px;background:#F5F0E8;border:2px dashed var(--stone-warm);border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--stone-warm)" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M21 21v-7m0 0h-7m7 0l-7-7"/></svg>
                        <p style="font-size:0.7rem;color:var(--muted);text-align:center;padding:0 10px;line-height:1.4;">The QR code becomes active once payment is confirmed</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($isConfirmed): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new QRCode(document.getElementById('qrcode'), {
        text: <?= json_encode($ticket->ticket_code . '|' . $ticket->event_name . '|' . $ticket->user_name) ?>,
        width:  148,
        height: 148,
        colorDark:  '#2C2416',
        colorLight: '#FFFFFF',
        correctLevel: QRCode.CorrectLevel.M
    });
});
</script>
<?php endif; ?>

<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
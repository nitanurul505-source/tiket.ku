<?php ob_start();
$prefill_event_id = $_GET['event_id'] ?? null;
?>
<div style="max-width:700px;margin:0 auto;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;font-size:0.78rem;color:var(--muted);">
        <a href="?controller=event&action=index" style="color:var(--blue-mid);text-decoration:none;font-weight:600;">Home</a>
        <span>›</span><span>Book Tickets</span>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert-error" style="margin-bottom:16px;">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'full'): ?>
    <div class="alert-error" style="margin-bottom:16px;">⚠ This event is fully booked.</div>
    <?php endif; ?>

    <div style="background:#fff;border-radius:16px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--shadow-card);">

        <!-- Header -->
        <div class="form-sidebar" style="padding:26px 32px;position:relative;overflow:hidden;">
            <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.07);pointer-events:none;"></div>
            <div style="position:relative;z-index:1;">
                <p style="font-size:0.67rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-bottom:6px;">Order Form</p>
                <h2 style="font-size:1.8rem;font-weight:800;color:#fff;">Book a Ticket</h2>
                <p style="color:rgba(255,255,255,0.6);font-size:0.83rem;margin-top:4px;">
                    Order as: <strong style="color:#93C5FD;"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong>
                </p>
            </div>
        </div>

        <div style="padding:28px 32px;">
            <form method="POST" action="?controller=ticket&action=create" style="display:flex;flex-direction:column;gap:22px;">

                <!-- Pilih Acara -->
                <div>
                    <label class="form-label">Select an Event</label>
                    <select name="event_id" id="eventSelect" required class="form-input" onchange="updatePreview()">
                        <option value="">— Select an Event —</option>
                        <?php foreach ($events as $ev): ?>
                        <option value="<?= $ev['id'] ?>"
                            data-price="<?= $ev['price'] ?>"
                            data-date="<?= date('d M Y, H:i', strtotime($ev['event_date'])) ?>"
                            data-venue="<?= htmlspecialchars($ev['venue']) ?>"
                            <?= $ev['id'] == $prefill_event_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ev['name']) ?> — Rp <?= number_format($ev['price'],0,',','.') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="eventPreview" style="display:none;margin-top:8px;padding:10px 14px;background:var(--blue-xpale);border-radius:8px;border:1px solid var(--blue-pale);font-size:0.82rem;display:flex;gap:16px;flex-wrap:wrap;align-items:center;">
                        <span style="color:var(--muted);">📅 <strong id="previewDate" style="color:var(--ink);"></strong></span>
                        <span style="color:var(--muted);">📍 <strong id="previewVenue" style="color:var(--ink);"></strong></span>
                        <span style="font-weight:800;color:var(--blue-mid);" id="previewPrice"></span>
                    </div>
                </div>

                <!-- ─── QTY PICKER ─── -->
                <div>
                    <label class="form-label">Number of Tickets</label>
                    <div style="display:flex;align-items:center;gap:0;border:1.5px solid var(--border);border-radius:10px;overflow:hidden;width:fit-content;background:#fff;">
                        <button type="button" id="qtyMinus"
                            onclick="changeQty(-1)"
                            style="width:44px;height:44px;border:none;background:#F8FAFF;cursor:pointer;font-size:1.2rem;font-weight:700;color:var(--blue-mid);transition:background 0.15s;display:flex;align-items:center;justify-content:center;border-right:1px solid var(--border);"
                            onmouseover="this.style.background='var(--blue-pale)'"
                            onmouseout="this.style.background='#F8FAFF'">−</button>
                        <input type="number" name="qty" id="qtyInput" value="1" min="1" max="<?= $maxQty ?? 10 ?>" readonly
                            style="width:64px;height:44px;text-align:center;border:none;outline:none;font-size:1rem;font-weight:800;color:var(--ink);background:#fff;cursor:default;">
                        <button type="button" id="qtyPlus"
                            onclick="changeQty(1)"
                            style="width:44px;height:44px;border:none;background:#F8FAFF;cursor:pointer;font-size:1.2rem;font-weight:700;color:var(--blue-mid);transition:background 0.15s;display:flex;align-items:center;justify-content:center;border-left:1px solid var(--border);"
                            onmouseover="this.style.background='var(--blue-pale)'"
                            onmouseout="this.style.background='#F8FAFF'">+</button>
                    </div>
                    <p style="font-size:0.73rem;color:var(--muted);margin-top:5px;">A maximum of 10 tickets per transaction.</p>

                    <!-- Total harga dinamis -->
                    <div id="totalBox" style="display:none;margin-top:10px;padding:12px 16px;background:var(--blue-xpale);border-radius:8px;border:1px solid var(--blue-pale);display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:0.84rem;color:var(--muted);">Total (<span id="totalQtyLabel">1</span> ticket)</span>
                        <span style="font-size:1.05rem;font-weight:800;color:var(--blue-mid);" id="totalPrice">Rp 0</span>
                    </div>
                </div>

                <!-- ─── CUSTOM ID ─── -->
                <div style="background:#EFF6FF;border:2px solid #BFDBFE;border-radius:12px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                        <div style="width:28px;height:28px;background:var(--blue-mid);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        </div>
                        <span style="font-size:0.8rem;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:var(--blue-deep);">Custom Ticket ID</span>
                        <span style="font-size:0.68rem;background:var(--blue-mid);color:#fff;padding:2px 8px;border-radius:4px;font-weight:700;">Opsional</span>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;align-items:end;">
                        <div>
                            <label class="form-label" style="color:var(--blue-deep);">Your Unique Prefix</label>
                            <input type="text" name="custom_prefix" id="customPrefix" class="form-input"
                                placeholder="Cth: BUDI" maxlength="6"
                                style="text-transform:uppercase;font-family:monospace;font-weight:700;font-size:0.95rem;background:#fff;border-color:#93C5FD;"
                                oninput="this.value=this.value.toUpperCase();updateIdPreview()">
                            <p style="font-size:0.7rem;color:var(--blue-soft);margin-top:3px;">Max 6 characters, letters &amp; numer.</p>
                        </div>
                        <div>
                            <label class="form-label" style="color:var(--blue-deep);">Preview Your Ticket Code</label>
                            <div style="font-family:'Courier New',monospace;font-size:0.95rem;font-weight:800;color:#fff;background:var(--blue-mid);border-radius:8px;padding:10px 14px;letter-spacing:0.08em;border:none;" id="idPreview">
                                TIX-<?= date('Ymd') ?>-XXXX
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ─── Payment Methods ─── -->
                <div>
                    <label class="form-label">Payment Methods</label>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                        <?php foreach ([
                            'transfer' => ['Transfer Bank',    '<path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z"/>'],
                            'card'     => ['Kartu Kredit',     '<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>'],
                            'cash'     => ['Tunai / Minimarket','<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>'],
                        ] as $val => [$label, $icon]): ?>
                        <label style="cursor:pointer;display:block;">
                            <input type="radio" name="payment_method" value="<?= $val ?>" class="pmr"
                                   style="display:none;" <?= $val==='transfer'?'checked':'' ?>
                                   onchange="hlPay()">
                            <div class="popt" data-v="<?= $val ?>"
                                 style="border:2px solid <?= $val==='transfer'?'var(--blue-mid)':'var(--border)' ?>;
                                        background:<?= $val==='transfer'?'var(--blue-pale)':'#fff' ?>;
                                        border-radius:10px;padding:14px 10px;text-align:center;
                                        transition:all 0.18s;">
                                <div style="width:40px;height:40px;border-radius:8px;
                                            background:<?= $val==='transfer'?'var(--blue-mid)':'#F1F5FF' ?>;
                                            display:flex;align-items:center;justify-content:center;
                                            margin:0 auto 8px;transition:all 0.18s;" class="popt-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                         stroke="<?= $val==='transfer'?'#fff':'var(--blue-soft)' ?>"
                                         stroke-width="1.8" class="popt-svg"><?= $icon ?></svg>
                                </div>
                                <p style="font-size:0.78rem;font-weight:700;color:<?= $val==='transfer'?'var(--blue-deep)':'var(--ink-soft)' ?>;" class="popt-label"><?= $label ?></p>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="alert-info">
                    Ticket status <strong>Pending</strong> until the admin verifies the payment.
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:4px;border-top:1px solid var(--border);">
                    <a href="?controller=event&action=index" class="btn-ghost">Back</a>
                    <button type="submit" class="btn-primary" style="padding:0.65rem 2rem;">
                        Order Confirmation →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ── Event preview ──
function updatePreview() {
    const sel = document.getElementById('eventSelect');
    const opt = sel.options[sel.selectedIndex];
    const box = document.getElementById('eventPreview');
    if (opt.value) {
        document.getElementById('previewDate').textContent  = opt.dataset.date;
        document.getElementById('previewVenue').textContent = opt.dataset.venue;
        window._unitPrice = parseInt(opt.dataset.price) || 0;
        document.getElementById('previewPrice').textContent = 'Rp ' + window._unitPrice.toLocaleString('id-ID');
        box.style.display = 'flex';
    } else {
        box.style.display = 'none';
        window._unitPrice = 0;
    }
    updateTotal();
}

// ── Qty picker ──
window._unitPrice = 0;

function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) + delta;
    val = Math.max(1, Math.min(10, val));
    input.value = val;
    updateTotal();
}

function updateTotal() {
    const qty   = parseInt(document.getElementById('qtyInput').value) || 1;
    const price = window._unitPrice || 0;
    const total = qty * price;
    const box   = document.getElementById('totalBox');

    document.getElementById('totalQtyLabel').textContent = qty;
    document.getElementById('totalPrice').textContent    = 'Rp ' + total.toLocaleString('id-ID');
    box.style.display = price > 0 ? 'flex' : 'none';
}

// ── Custom ID preview ──
function updateIdPreview() {
    const p = document.getElementById('customPrefix').value.trim() || 'TIX';
    document.getElementById('idPreview').textContent = p + '-<?= date('Ymd') ?>-XXXX';
}

// ── Payment method highlight ──
function hlPay() {
    document.querySelectorAll('.popt').forEach(el => {
        el.style.borderColor = 'var(--border)';
        el.style.background  = '#fff';
        el.querySelector('.popt-icon').style.background = '#F1F5FF';
        el.querySelector('.popt-svg').setAttribute('stroke', 'var(--blue-soft)');
        el.querySelector('.popt-label').style.color = 'var(--ink-soft)';
    });
    const checked = document.querySelector('.pmr:checked');
    if (checked) {
        const opt  = document.querySelector('.popt[data-v="' + checked.value + '"]');
        if (opt) {
            opt.style.borderColor = 'var(--blue-mid)';
            opt.style.background  = 'var(--blue-pale)';
            opt.querySelector('.popt-icon').style.background = 'var(--blue-mid)';
            opt.querySelector('.popt-svg').setAttribute('stroke', '#fff');
            opt.querySelector('.popt-label').style.color = 'var(--blue-deep)';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updatePreview();
    hlPay();
});
</script>
<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
<?php ob_start(); ?>
<div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:1.9rem;font-weight:700;color:var(--charcoal);">User Management</h1>
            <p style="color:var(--muted);font-size:0.84rem;"><?= count($users) ?> registered users</p>
        </div>
    </div>
    <div style="background:white;border-radius:14px;border:1px solid var(--stone-warm);overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone Number</th><th style="text-align:right;">Action</th></tr></thead>
                <tbody>
                    <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td style="color:var(--muted);font-size:0.76rem;font-weight:600;"><?= str_pad($i+1,2,'0',STR_PAD_LEFT) ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px;">
                                <div style="width:30px;height:30px;border-radius:50%;background:#F5EDE4;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--terracotta);font-size:0.8rem;flex-shrink:0;"><?= strtoupper(substr($u['name'],0,1)) ?></div>
                                <span style="font-weight:600;color:var(--charcoal);"><?= htmlspecialchars($u['name']) ?></span>
                            </div>
                        </td>
                        <td style="color:var(--muted);font-size:0.85rem;"><?= htmlspecialchars($u['email']) ?></td>
                        <td style="color:var(--muted);font-size:0.85rem;"><?= htmlspecialchars($u['phone']) ?></td>
                        <td style="text-align:right;">
                            <?php if ($u['email'] !== 'admin@tiketku.com'): ?>
                            <a href="?controller=user&action=delete&id=<?= $u['id'] ?>" style="background:#FEF2F2;color:#DC2626;padding:4px 10px;border-radius:5px;font-size:0.78rem;font-weight:700;text-decoration:none;border:1px solid #FECACA;" onclick="return confirm('Hapus user ini?');">Delete</a>
                            <?php else: ?><span style="font-size:0.76rem;color:var(--muted);font-style:italic;">Admin</span><?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($users)): ?><tr><td colspan="5" style="text-align:center;color:var(--muted);padding:40px;">Empty.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
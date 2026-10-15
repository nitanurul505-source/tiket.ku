<?php ob_start(); ?>
<div style="max-width:680px;margin:0 auto;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:18px;font-size:0.78rem;color:var(--muted);">
        <a href="?controller=event&action=index" style="color:var(--blue-mid);text-decoration:none;font-weight:600;">Home</a>
        <span>›</span><span>Add Event</span>
    </div>

    <?php if (!empty($uploadError)): ?>
    <div class="alert-error" style="margin-bottom:16px;">⚠ <?= htmlspecialchars($uploadError) ?></div>
    <?php endif; ?>

    <div style="background:#fff;border-radius:16px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--shadow-card);">
        <div class="form-sidebar" style="padding:24px 30px;">
            <div style="position:relative;z-index:1;">
                <p style="font-size:0.67rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-bottom:5px;">Admin Panel</p>
                <h2 style="font-size:1.6rem;font-weight:800;color:#fff;">Add a New Event</h2>
            </div>
        </div>

        <div style="padding:26px 30px;">
            <!-- The enctype attribute is required for file uploads -->
            <form method="POST" action="?controller=event&action=create"
                  enctype="multipart/form-data"
                  style="display:flex;flex-direction:column;gap:16px;">

                <div>
                    <label class="form-label">Event Name</label>
                    <input type="text" name="name" required class="form-input" placeholder="Tech Conference 2026">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label class="form-label">Date &amp; Time</label>
                        <input type="datetime-local" name="event_date" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" required class="form-input" placeholder="Auditorium Utama">
                    </div>
                </div>

                <div>
                    <label class="form-label">Location Details</label>
                    <input type="text" name="location_detail" class="form-input" placeholder="Gedung A Lt. 2, Jl. Pendidikan No. 1">
                </div>

                <!-- UPLOAD FOTO -->
                <div>
                    <label class="form-label">Event Photos / Posters</label>
                    <!-- Area drag & drop upload -->
                    <div id="dropZone"
                         style="border:2px dashed var(--border);border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all 0.2s;background:var(--bg);position:relative;"
                         onclick="document.getElementById('imageFileInput').click()"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event)">
                        <input type="file" id="imageFileInput" name="image_file"
                               accept="image/jpeg,image/png,image/webp"
                               style="display:none;" onchange="previewImage(event)">

                        <div id="dropContent">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--muted-light)" stroke-width="1.5" style="margin:0 auto 10px;display:block;">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <p style="font-size:0.88rem;font-weight:600;color:var(--muted);">Click or drag a photo here</p>
                            <p style="font-size:0.75rem;color:var(--muted-light);margin-top:4px;">JPG, PNG, WebP · Maks 5 MB</p>
                        </div>

                        <!-- Preview the image after selecting it -->
                        <div id="previewContainer" style="display:none;">
                            <img id="previewImg" style="max-height:180px;border-radius:8px;object-fit:cover;max-width:100%;" src="" alt="Preview">
                            <p id="previewName" style="font-size:0.78rem;color:var(--muted);margin-top:8px;"></p>
                            <button type="button" onclick="clearImage(event)"
                                    style="margin-top:6px;background:#FEE2E2;color:#DC2626;border:none;border-radius:5px;padding:4px 12px;font-size:0.75rem;font-weight:700;cursor:pointer;">
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- Or use the URL -->
                    <div style="display:flex;align-items:center;gap:10px;margin-top:10px;">
                        <div style="flex:1;height:1px;background:var(--border);"></div>
                        <span style="font-size:0.72rem;color:var(--muted);white-space:nowrap;">or use the URL</span>
                        <div style="flex:1;height:1px;background:var(--border);"></div>
                    </div>
                    <input type="url" name="image_url" class="form-input" style="margin-top:8px;"
                           placeholder="https://... (optional, leave blank if you've uploaded the file above)">
                </div>

                <div>
                    <label class="form-label">Event Description</label>
                    <textarea name="description" class="form-input" rows="3"
                              placeholder="Tell us about this event..."></textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" min="1" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Lowest Price (Rp)</label>
                        <input type="number" name="price" min="0" required class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Organizer</label>
                    <select name="organizer_id" required class="form-input">
                        <option value="">— Select —</option>
                        <?php foreach ($organizers as $o): ?>
                        <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:8px;border-top:1px solid var(--border);">
                    <a href="?controller=event&action=index" class="btn-ghost">Cancel</a>
                    <button type="submit" class="btn-primary">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(e) {
    const file = e.target.files[0];
    if (!file) return;
    showPreview(file);
}
function showPreview(file) {
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById('previewImg').src = ev.target.result;
        document.getElementById('previewName').textContent = file.name + ' (' + (file.size/1024).toFixed(0) + ' KB)';
        document.getElementById('dropContent').style.display    = 'none';
        document.getElementById('previewContainer').style.display = 'block';
        document.getElementById('dropZone').style.borderColor  = 'var(--blue-soft)';
        document.getElementById('dropZone').style.background   = 'var(--blue-xpale)';
    };
    reader.readAsDataURL(file);
}
function clearImage(e) {
    e.stopPropagation();
    document.getElementById('imageFileInput').value = '';
    document.getElementById('previewImg').src = '';
    document.getElementById('dropContent').style.display    = 'block';
    document.getElementById('previewContainer').style.display = 'none';
    document.getElementById('dropZone').style.borderColor = 'var(--border)';
    document.getElementById('dropZone').style.background  = 'var(--bg)';
}
function handleDragOver(e) {
    e.preventDefault();
    document.getElementById('dropZone').style.borderColor = 'var(--blue-soft)';
    document.getElementById('dropZone').style.background  = 'var(--blue-xpale)';
}
function handleDragLeave(e) {
    document.getElementById('dropZone').style.borderColor = 'var(--border)';
    document.getElementById('dropZone').style.background  = 'var(--bg)';
}
function handleDrop(e) {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('imageFileInput').files = dt.files;
    showPreview(file);
}
</script>
<?php $content = ob_get_clean(); require_once __DIR__ . '/../layouts/main.php'; ?>
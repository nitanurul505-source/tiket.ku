<?php ob_start(); ?>
<div style="max-width:680px;margin:0 auto;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:18px;font-size:0.78rem;color:var(--muted);">
        <a href="?controller=event&action=index" style="color:var(--blue-mid);text-decoration:none;font-weight:600;">Home</a>
        <span>›</span>
        <a href="?controller=event&action=detail&id=<?= $this->event->id ?>" style="color:var(--blue-mid);text-decoration:none;font-weight:600;"><?= htmlspecialchars($this->event->name) ?></a>
        <span>›</span><span>Edit</span>
    </div>

    <?php if (!empty($uploadError)): ?>
    <div class="alert-error" style="margin-bottom:16px;">⚠ <?= htmlspecialchars($uploadError) ?></div>
    <?php endif; ?>

    <div style="background:#fff;border-radius:16px;border:1px solid var(--border);overflow:hidden;box-shadow:var(--shadow-card);">
        <div style="background:linear-gradient(135deg,#1e3a8a,#2563eb);padding:22px 30px;position:relative;overflow:hidden;">
            <div style="position:absolute;right:-20px;top:-20px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.06);pointer-events:none;"></div>
            <p style="font-size:0.67rem;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-bottom:4px;">Edit Event</p>
            <h2 style="font-size:1.5rem;font-weight:800;color:#fff;"><?= htmlspecialchars($this->event->name) ?></h2>
        </div>

        <div style="padding:26px 30px;">
            <form method="POST" action="?controller=event&action=edit&id=<?= $this->event->id ?>"
                  enctype="multipart/form-data"
                  style="display:flex;flex-direction:column;gap:16px;">

                <!-- Save the URL of the existing photo -->
                <input type="hidden" name="image_url_existing" value="<?= htmlspecialchars($this->event->image_url ?? '') ?>">

                <div>
                    <label class="form-label">Event Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($this->event->name) ?>" required class="form-input">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label class="form-label">Date &amp; Time</label>
                        <input type="datetime-local" name="event_date" value="<?= date('Y-m-d\TH:i', strtotime($this->event->event_date)) ?>" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" value="<?= htmlspecialchars($this->event->venue) ?>" required class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Location Details</label>
                    <input type="text" name="location_detail" value="<?= htmlspecialchars($this->event->location_detail ?? '') ?>" class="form-input">
                </div>

                <!-- Change photo -->
                <div>
                    <label class="form-label">Event Photos / Posters</label>

                    <!-- Preview the photo now -->
                    <?php if (!empty($this->event->image_url)): ?>
                    <div id="currentPhotoBox" style="margin-bottom:10px;padding:10px;background:var(--bg);border:1px solid var(--border);border-radius:10px;display:flex;align-items:center;gap:12px;">
                        <img src="<?= htmlspecialchars($this->event->image_url) ?>"
                             style="width:80px;height:60px;object-fit:cover;border-radius:6px;"
                             onerror="this.style.display='none'">
                        <div>
                            <p style="font-size:0.78rem;font-weight:600;color:var(--ink);">Current photo</p>
                            <p style="font-size:0.72rem;color:var(--muted);margin-top:1px;">Upload a new photo below to replace it</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- New upload drop zone -->
                    <div id="dropZone"
                         style="border:2px dashed var(--border);border-radius:12px;padding:20px;text-align:center;cursor:pointer;transition:all 0.2s;background:var(--bg);"
                         onclick="document.getElementById('imageFileInput').click()"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event)">
                        <input type="file" id="imageFileInput" name="image_file"
                               accept="image/jpeg,image/png,image/webp"
                               style="display:none;" onchange="previewImage(event)">

                        <div id="dropContent">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--muted-light)" stroke-width="1.5" style="margin:0 auto 8px;display:block;">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p style="font-size:0.84rem;font-weight:600;color:var(--muted);">Upload a new photo (optional)</p>
                            <p style="font-size:0.72rem;color:var(--muted-light);margin-top:3px;">JPG, PNG, WebP · Maks 5 MB</p>
                        </div>

                        <div id="previewContainer" style="display:none;">
                            <img id="previewImg" style="max-height:150px;border-radius:8px;object-fit:cover;max-width:100%;" src="" alt="Preview">
                            <p id="previewName" style="font-size:0.75rem;color:var(--muted);margin-top:6px;"></p>
                            <button type="button" onclick="clearImage(event)"
                                    style="margin-top:5px;background:#FEE2E2;color:#DC2626;border:none;border-radius:5px;padding:3px 10px;font-size:0.73rem;font-weight:700;cursor:pointer;">
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Atau URL -->
                    <div style="display:flex;align-items:center;gap:10px;margin-top:10px;">
                        <div style="flex:1;height:1px;background:var(--border);"></div>
                        <span style="font-size:0.72rem;color:var(--muted);white-space:nowrap;">or replace with a URL</span>
                        <div style="flex:1;height:1px;background:var(--border);"></div>
                    </div>
                    <input type="url" name="image_url" class="form-input" style="margin-top:8px;"
                           placeholder="https://... (kosongkan jika tidak ingin ganti)">
                </div>

                <div>
                    <label class="form-label">Event Description</label>
                    <textarea name="description" class="form-input" rows="3"><?= htmlspecialchars($this->event->description ?? '') ?></textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" value="<?= htmlspecialchars($this->event->capacity) ?>" min="1" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Lower Price (Rp)</label>
                        <input type="number" name="price" value="<?= htmlspecialchars($this->event->price) ?>" min="0" required class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Organizer</label>
                    <select name="organizer_id" required class="form-input">
                        <?php foreach ($organizers as $o): ?>
                        <option value="<?= $o['id'] ?>" <?= $o['id'] == $this->event->organizer_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($o['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:8px;border-top:1px solid var(--border);">
                    <a href="?controller=event&action=detail&id=<?= $this->event->id ?>" class="btn-ghost">Cancel</a>
                    <button type="submit" class="btn-primary">Save Changes</button>
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
        // Sembunyikan foto lama
        const cur = document.getElementById('currentPhotoBox');
        if (cur) cur.style.opacity = '0.4';
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
    const cur = document.getElementById('currentPhotoBox');
    if (cur) cur.style.opacity = '1';
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
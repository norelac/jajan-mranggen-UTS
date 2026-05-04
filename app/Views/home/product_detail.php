<?= $this->extend('layouts/main') ?>
<?= $this->section('styles') ?>
<!-- Leaflet.js CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    #seller-map { height: 300px; border-radius: 16px; z-index: 1; }
    .star-rating { display: flex; flex-direction: row-reverse; gap: 4px; }
    .star-rating input { display: none; }
    .star-rating label { font-size: 2rem; color: #ddd; cursor: pointer; transition: color .15s; }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label { color: #FFA500; }
    .review-card { background: #fff; border-radius: 14px; padding: 18px; border: 1px solid #f0f0f0; }
    .stars-display i { color: #FFA500; font-size: .85rem; }
    .stars-display i.empty { color: #ddd; }
    .tag-badge { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size:.78rem; font-weight:600; margin-right:4px; margin-bottom:4px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" style="color:var(--primary)">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/produk" style="color:var(--primary)">Produk</a></li>
            <li class="breadcrumb-item active"><?= esc($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Image -->
        <div class="col-lg-5">
            <div class="rounded-4 overflow-hidden" style="box-shadow:0 10px 40px rgba(0,0,0,.1);">
                <?php if ($product['image'] && file_exists(FCPATH . $product['image'])): ?>
                    <img src="/<?= esc($product['image']) ?>" class="img-fluid w-100" alt="<?= esc($product['name']) ?>" style="max-height:420px; object-fit:cover;">
                <?php else: ?>
                    <div style="height:350px;background:linear-gradient(135deg,#FFE0D0,#FFB89A);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-bowl-food" style="font-size:8rem;color:var(--primary);opacity:.5;"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tags -->
            <?php if (! empty($tags)): ?>
            <div class="mt-3">
                <?php foreach ($tags as $tag): ?>
                    <a href="/tag/<?= esc($tag['slug']) ?>" class="tag-badge text-decoration-none" style="background:<?= esc($tag['color']) ?>22; color:<?= esc($tag['color']) ?>; border: 1px solid <?= esc($tag['color']) ?>44;">
                        #<?= esc($tag['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7">
            <span class="badge rounded-pill px-3 py-2 mb-3" style="background:rgba(255,107,53,.1);color:var(--primary);font-size:.85rem;">
                <?= esc($product['category_name']) ?>
            </span>
            <h1 style="font-size:1.8rem;font-weight:700;"><?= esc($product['name']) ?></h1>

            <!-- Star Rating Display -->
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="d-flex align-items-center gap-1">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                        <i class="fas fa-star <?= $s <= round($rating['avg']) ? '' : 'empty' ?> stars-display"></i>
                    <?php endfor; ?>
                </div>
                <span class="fw-700" style="font-size:1.1rem;"><?= $rating['avg'] ?></span>
                <span class="text-muted small">(<?= $rating['total'] ?> ulasan)</span>
            </div>

            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="font-size:2rem;font-weight:800;color:var(--primary);">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                <span class="badge <?= $product['stock'] > 0 ? 'badge-active' : 'badge-inactive' ?> px-3 py-2" style="font-size:.85rem;">
                    <?= $product['stock'] > 0 ? 'Tersedia (' . $product['stock'] . ')' : 'Stok Habis' ?>
                </span>
            </div>

            <!-- Seller Info -->
            <div class="d-flex align-items-center gap-2 mb-4 p-3 rounded-3" style="background:#FFF8F0;">
                <div style="width:40px;height:40px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">
                    <?= strtoupper(substr($product['seller_name'], 0, 1)) ?>
                </div>
                <div>
                    <div class="fw-600 small"><?= esc($product['seller_name']) ?></div>
                    <div class="text-muted" style="font-size:.75rem;"><i class="fas fa-map-marker-alt me-1"></i><?= esc($seller['address'] ?? 'Mranggen, Demak') ?></div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <h6 class="fw-700 mb-2">Deskripsi</h6>
                <p class="text-muted" style="line-height:1.8;"><?= nl2br(esc($product['description'] ?? 'Tidak ada deskripsi.')) ?></p>
            </div>

            <!-- Specs -->
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="text-center p-3 rounded-3" style="background:#f8f9fa;">
                        <i class="fas fa-weight-hanging text-muted mb-1 d-block"></i>
                        <div class="fw-600 small"><?= $product['weight'] ?> gram</div>
                        <div class="text-muted" style="font-size:.75rem;">Berat</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 rounded-3" style="background:#f8f9fa;">
                        <i class="fas fa-boxes-stacked text-muted mb-1 d-block"></i>
                        <div class="fw-600 small"><?= $product['stock'] ?> pcs</div>
                        <div class="text-muted" style="font-size:.75rem;">Stok</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-3 rounded-3" style="background:#f8f9fa;">
                        <i class="fas fa-truck text-muted mb-1 d-block"></i>
                        <div class="fw-600 small">Tersedia</div>
                        <div class="text-muted" style="font-size:.75rem;">Pengiriman</div>
                    </div>
                </div>
            </div>

            <!-- Add to Cart -->
            <?php if (session()->get('role') === 'pembeli' && $product['stock'] > 0): ?>
            <form action="/pembeli/cart/add" method="POST" class="d-flex gap-3 align-items-center">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="d-flex align-items-center border rounded-3 overflow-hidden" style="border-color:#ddd !important;">
                    <button type="button" class="btn border-0 px-3" onclick="this.nextElementSibling.stepDown()">-</button>
                    <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>" class="form-control border-0 text-center" style="width:60px;border-radius:0;">
                    <button type="button" class="btn border-0 px-3" onclick="this.previousElementSibling.stepUp()">+</button>
                </div>
                <button type="submit" class="btn btn-primary-custom flex-grow-1" style="border-radius:25px;padding:12px;">
                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                </button>
            </form>
            <?php elseif (! session()->get('isLoggedIn')): ?>
            <a href="/auth/login" class="btn btn-primary-custom w-100" style="border-radius:25px;padding:12px;">
                <i class="fas fa-right-to-bracket me-2"></i>Login untuk Membeli
            </a>
            <?php elseif ($product['stock'] == 0): ?>
            <button class="btn btn-secondary w-100 rounded-pill" disabled>Stok Habis</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ═══════════════ PETA INTERAKTIF PENJUAL ═══════════════ -->
    <div class="mt-5">
        <h3 class="section-title">Lokasi <span>Penjual</span></h3>
        <div class="section-divider"></div>
        <div class="card border-0 rounded-4 overflow-hidden shadow-sm">
            <div id="seller-map"></div>
        </div>
        <div class="d-flex align-items-center gap-2 mt-3 text-muted small">
            <i class="fas fa-info-circle"></i>
            <span>Lokasi perkiraan berdasarkan alamat penjual. Koordinat dideteksi otomatis via Nominatim/OpenStreetMap.</span>
        </div>
    </div>

    <!-- ═══════════════ ULASAN & RATING ═══════════════ -->
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="section-title mb-0">Ulasan <span>Pembeli</span></h3>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                        <i class="fas fa-star" style="color:<?= $s <= round($rating['avg']) ? '#FFA500' : '#ddd' ?>;"></i>
                    <?php endfor; ?>
                </div>
                <strong><?= $rating['avg'] ?>/5</strong>
                <span class="text-muted small">(<?= $rating['total'] ?> ulasan)</span>
            </div>
        </div>
        <div class="section-divider"></div>

        <!-- Rating Distribution -->
        <?php if ($rating['total'] > 0): ?>
        <div class="row g-4 mb-5">
            <div class="col-md-3 text-center">
                <div style="font-size:4rem;font-weight:800;color:var(--primary); line-height:1;"><?= $rating['avg'] ?></div>
                <div class="d-flex justify-content-center gap-1 my-2">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                        <i class="fas fa-star" style="color:<?= $s <= round($rating['avg']) ? '#FFA500' : '#ddd' ?>;font-size:1.2rem;"></i>
                    <?php endfor; ?>
                </div>
                <div class="text-muted small"><?= $rating['total'] ?> ulasan</div>
            </div>
            <div class="col-md-9 d-flex flex-column justify-content-center gap-2">
                <?php for ($star = 5; $star >= 1; $star--): ?>
                    <?php
                    $count = array_reduce($reviews, fn($c, $r) => $c + ($r['rating'] == $star ? 1 : 0), 0);
                    $pct   = $rating['total'] > 0 ? round(($count / $rating['total']) * 100) : 0;
                    ?>
                    <div class="d-flex align-items-center gap-3">
                        <span class="small fw-600" style="width:40px;"><?= $star ?> <i class="fas fa-star" style="color:#FFA500;font-size:.7rem;"></i></span>
                        <div class="flex-grow-1 bg-light rounded-pill overflow-hidden" style="height:10px;">
                            <div class="rounded-pill" style="width:<?= $pct ?>%;height:100%;background:linear-gradient(90deg,#FFA500,#FF6B35);transition:width .8s;"></div>
                        </div>
                        <span class="text-muted small" style="width:35px;"><?= $count ?></span>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Review List -->
        <?php if (empty($reviews)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-comment-slash fa-3x mb-3 d-block opacity-25"></i>
                <p>Belum ada ulasan untuk produk ini. Jadilah yang pertama!</p>
            </div>
        <?php else: ?>
            <div class="row g-3 mb-5">
                <?php foreach ($reviews as $review): ?>
                <div class="col-md-6">
                    <div class="review-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <?php if (isset($review['avatar']) && $review['avatar'] && file_exists(FCPATH . $review['avatar'])): ?>
                                <img src="/<?= esc($review['avatar']) ?>" width="38" height="38" class="rounded-circle object-fit-cover">
                            <?php else: ?>
                                <div style="width:38px;height:38px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.9rem;"><?= strtoupper(substr($review['full_name'], 0, 1)) ?></div>
                            <?php endif; ?>
                            <div>
                                <div class="fw-600 small"><?= esc($review['full_name']) ?></div>
                                <div class="text-muted" style="font-size:.72rem;"><?= date('d M Y', strtotime($review['created_at'])) ?></div>
                            </div>
                            <div class="ms-auto d-flex gap-1">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <i class="fas fa-star" style="color:<?= $s <= $review['rating'] ? '#FFA500' : '#ddd' ?>;font-size:.8rem;"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if ($review['comment']): ?>
                            <p class="mb-0 text-muted small" style="line-height:1.6;"><?= esc($review['comment']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Write Review Form (Pembeli that has purchased & not reviewed yet) -->
        <?php if (session()->get('role') === 'pembeli'): ?>
        <div class="card border-0 rounded-4 p-4" style="background:linear-gradient(135deg,#FFF8F0,#FFF0E8);">
            <h5 class="fw-700 mb-1">Tulis Ulasan</h5>
            <?php if ($canReview && ! $hasReviewed): ?>
                <p class="text-muted small mb-3">Bagikan pengalamanmu setelah menerima produk ini.</p>
                <form action="/reviews/store" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="slug" value="<?= esc($product['slug']) ?>">
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Rating <span class="text-danger">*</span></label>
                        <div class="star-rating" id="starRating">
                            <?php for ($s = 5; $s >= 1; $s--): ?>
                                <input type="radio" id="star<?= $s ?>" name="rating" value="<?= $s ?>" <?= $s === 5 ? 'required' : '' ?>>
                                <label for="star<?= $s ?>" title="<?= $s ?> bintang"><i class="fas fa-star"></i></label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Komentar (Opsional)</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Ceritakan pengalamanmu dengan produk ini..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary-custom px-4"><i class="fas fa-paper-plane me-2"></i>Kirim Ulasan</button>
                </form>
            <?php elseif ($hasReviewed): ?>
                <div class="alert alert-success mb-0"><i class="fas fa-check-circle me-2"></i>Anda sudah memberikan ulasan untuk produk ini.</div>
            <?php else: ?>
                <div class="alert mb-0" style="background:#FFF;border:1px solid #f0f0f0;">
                    <i class="fas fa-lock me-2 text-muted"></i>
                    <span class="text-muted small">Anda hanya dapat mengulas produk yang sudah Anda terima. Pesan sekarang untuk bisa memberikan ulasan!</span>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Related Products -->
    <?php if (! empty($related)): ?>
    <div class="mt-5">
        <h3 class="section-title">Produk <span>Serupa</span></h3>
        <div class="section-divider"></div>
        <div class="row g-4">
            <?php foreach ($related as $rel): ?>
            <div class="col-6 col-md-3">
                <div class="card product-card h-100">
                    <?php if ($rel['image'] && file_exists(FCPATH . $rel['image'])): ?>
                        <img src="/<?= esc($rel['image']) ?>" class="card-img-top" alt="<?= esc($rel['name']) ?>">
                    <?php else: ?>
                        <div class="product-img-placeholder"><i class="fas fa-bowl-food"></i></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h6 class="fw-600" style="font-size:.88rem;"><a href="/produk/<?= esc($rel['slug']) ?>" class="text-dark text-decoration-none"><?= esc($rel['name']) ?></a></h6>
                        <div class="price">Rp <?= number_format($rel['price'], 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Leaflet.js -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ─── Leaflet Map + Nominatim Geocoding ───────────────────────────────────────
const sellerAddress = <?= json_encode($seller['address'] ?? 'Mranggen, Demak, Jawa Tengah') ?>;
const sellerName    = <?= json_encode($product['seller_name']) ?>;
const sellerLat     = <?= json_encode($seller['latitude']  ?? null) ?>;
const sellerLng     = <?= json_encode($seller['longitude'] ?? null) ?>;

const defaultCenter = [-7.0222, 110.5303]; // Mranggen, Demak

// Initialize map
const map = L.map('seller-map').setView(defaultCenter, 13);

// OpenStreetMap tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 19
}).addTo(map);

// Custom marker icon
const markerIcon = L.divIcon({
    html: '<div style="width:40px;height:40px;background:var(--primary, #FF6B35);border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,.3);"><i class="fas fa-store" style="transform:rotate(45deg);color:#fff;font-size:1rem;"></i></div>',
    className: '',
    iconSize: [40, 40],
    iconAnchor: [20, 40],
    popupAnchor: [0, -44]
});

function addMarker(lat, lng) {
    const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
    marker.bindPopup(`
        <div style="font-family:Poppins,sans-serif;min-width:180px;">
            <strong style="color:#FF6B35;">${sellerName}</strong><br>
            <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>${sellerAddress}</small>
        </div>
    `).openPopup();
    map.setView([lat, lng], 15);
}

// Use stored coordinates if available, else geocode via Nominatim
if (sellerLat && sellerLng) {
    addMarker(sellerLat, sellerLng);
} else {
    // Geocoding via Nominatim (free, no API key needed)
    const query = encodeURIComponent(sellerAddress + ', Indonesia');
    fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json&limit=1`, {
        headers: { 'Accept-Language': 'id', 'User-Agent': 'JajanMranggen/1.0' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.length > 0) {
            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);
            addMarker(lat, lng);

            // Save coordinates to server for future use (fire & forget)
            fetch('/api/geocode/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({
                    user_id: <?= json_encode($product['seller_id']) ?>,
                    lat, lng,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            }).catch(() => {}); // Ignore errors
        } else {
            // Fallback: show default Mranggen area with a note
            L.marker(defaultCenter, { icon: markerIcon }).addTo(map)
                .bindPopup(`<strong>${sellerName}</strong><br><small>Mranggen, Demak, Jawa Tengah</small>`).openPopup();
        }
    })
    .catch(() => {
        L.marker(defaultCenter, { icon: markerIcon }).addTo(map)
            .bindPopup(`<strong>${sellerName}</strong><br><small>${sellerAddress}</small>`).openPopup();
    });
}
</script>
<?= $this->endSection() ?>

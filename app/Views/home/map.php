<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="section-title">Peta <span>Tempat Makan</span></h1>
            <div class="section-divider"></div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Map Column -->
        <div class="col-lg-8">
            <!-- Map -->
            <div class="card border-0 rounded-4 shadow-sm h-100" style="min-height: 600px;">
                <div id="map" style="height: 100%; border-radius: 1rem; width: 100%;"></div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Search & Filter -->
            <div class="card border-0 rounded-4 shadow-sm p-3 mb-3">
                <h5 class="fw-700 mb-3"><i class="fas fa-filter me-2 text-primary\"></i>Filter</h5>

                <!-- Location Input -->
                <div class="mb-3">
                    <label class="form-label fw-600 small">Lokasi Anda</label>
                    <div class="input-group">
                        <input type="text" id="locationInput" class="form-control" placeholder="Cari lokasi...">
                        <button class="btn btn-primary-custom" type="button" id="geolocateBtn" title="Gunakan lokasi saat ini">
                            <i class="fas fa-location-dot"></i>
                        </button>
                    </div>
                </div>

                <!-- Radius Filter -->
                <div class="mb-3">
                    <label class="form-label fw-600 small">Jarak Jangkauan: <span id="radiusValue">5</span> km</label>
                    <input type="range" id="radiusSlider" class="form-range" min="0.5" max="20" step="0.5" value="5">
                </div>

                <!-- Category Filter -->
                <div class="mb-3">
                    <label class="form-label fw-600 small">Kategori</label>
                    <div id="categoryFilter" style="max-height: 150px; overflow-y: auto;">
                        <div class="form-check">
                            <input class="form-check-input category-filter" type="checkbox" value="all" id="allCategories" checked>
                            <label class="form-check-label" for="allCategories">Semua Kategori</label>
                        </div>
                        <?php foreach ($categories as $cat): ?>
                        <div class="form-check">
                            <input class="form-check-input category-filter" type="checkbox" value="<?= $cat['id'] ?>" id="cat_<?= $cat['id'] ?>">
                            <label class="form-check-label" for="cat_<?= $cat['id'] ?>"><?= esc($cat['name']) ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tag Filter -->
                <?php if (!empty($tags)): ?>
                <div class="mb-3">
                    <label class="form-label fw-600 small">Tag</label>
                    <div style="max-height: 150px; overflow-y: auto;">
                        <?php foreach ($tags as $tag): ?>
                        <span class="badge p-2 m-1" style="background-color: <?= esc($tag['color']) ?>; cursor: pointer;" class="tag-filter" data-tag-id="<?= $tag['id'] ?>">
                            <?php if (!empty($tag['icon'])): ?><i class="fas <?= esc($tag['icon']) ?> me-1"></i><?php endif; ?>
                            <?= esc($tag['name']) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <button class="btn btn-primary-custom w-100" id="filterBtn"><i class="fas fa-search me-2"></i>Cari</button>
            </div>

            <!-- Results -->
            <div class="card border-0 rounded-4 shadow-sm p-3">
                <h5 class="fw-700 mb-3"><i class="fas fa-list me-2 text-primary\"></i>Hasil (<span id="resultCount">0</span>)</h5>
                <div id="resultsList" style="max-height: 400px; overflow-y: auto;">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-map fa-2x mb-2 d-block"></i>
                        <small>Gunakan peta untuk menemukan tempat makan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<!-- Leaflet MarkerCluster -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.0/MarkerCluster.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.0/MarkerCluster.Default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.0/leaflet.markercluster.min.js"></script>

<style>
    #map {
        position: relative;
    }

    .marker-icon-custom {
        background: var(--primary);
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .leaflet-popup-content {
        margin: 0;
        width: 250px !important;
    }

    .popup-place-name {
        font-weight: 600;
        font-size: 1rem;
        color: var(--dark);
        margin: 0 0 4px 0;
    }

    .popup-rating {
        color: #ffc107;
        font-size: 0.85rem;
        margin-bottom: 8px;
    }

    .popup-distance {
        color: #666;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }

    .popup-btn {
        display: inline-block;
        padding: 6px 12px;
        background: var(--primary);
        color: white;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .popup-btn:hover {
        background: var(--primary-dark);
        text-decoration: none;
    }

    .result-item {
        padding: 12px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: all 0.2s;
    }

    .result-item:hover {
        background: rgba(255, 107, 53, 0.05);
    }

    .result-item.active {
        background: rgba(255, 107, 53, 0.1);
        border-left: 3px solid var(--primary);
    }

    .result-item-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .result-item-rating {
        color: #ffc107;
        font-size: 0.75rem;
        margin-bottom: 4px;
    }

    .result-item-distance {
        font-size: 0.75rem;
        color: #666;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map').setView([-7.2975, 112.7554], 13); // Default: Surabaya

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    let userLocation = null;
    let markers = L.markerClusterGroup();
    let allProducts = <?= json_encode($products) ?>;
    let filteredProducts = allProducts;
    let userMarker = null;

    // Geolocation button
    document.getElementById('geolocateBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                // Add user marker
                if (userMarker) {
                    map.removeLayer(userMarker);
                }
                userMarker = L.marker(userLocation, {
                    icon: L.icon({
                        iconUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iOCIgZmlsbD0iIzQyODVGNCIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIyIi8+Cjwvc3ZnPg==',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16],
                    })
                }).addTo(map);

                map.setView(userLocation, 15);
                updateResults();
            });
        }
    });

    // Radius slider
    document.getElementById('radiusSlider').addEventListener('input', function() {
        document.getElementById('radiusValue').textContent = this.value;
        updateResults();
    });

    // Filter button
    document.getElementById('filterBtn').addEventListener('click', updateResults);

    // Category filter
    document.getElementById('allCategories').addEventListener('change', function() {
        if (this.checked) {
            document.querySelectorAll('.category-filter').forEach(cb => cb.checked = false);
            this.checked = true;
        }
    });

    document.querySelectorAll('.category-filter:not(#allCategories)').forEach(cb => {
        cb.addEventListener('change', function() {
            document.getElementById('allCategories').checked = false;
        });
    });

    function updateResults() {
        if (!userLocation) {
            alert('Silakan aktifkan lokasi Anda terlebih dahulu');
            return;
        }

        const radius = parseFloat(document.getElementById('radiusSlider').value);
        const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked')).map(cb => cb.value);

        // Filter products
        filteredProducts = allProducts.filter(product => {
            if (!product.latitude || !product.longitude) return false;

            // Calculate distance
            const distance = calculateDistance(userLocation.lat, userLocation.lng, product.latitude, product.longitude);
            if (distance > radius) return false;

            // Filter by category
            if (selectedCategories.length > 0 && !selectedCategories.includes('all')) {
                if (!selectedCategories.includes(product.category_id.toString())) return false;
            }

            product.distance = distance;
            return true;
        });

        // Sort by distance
        filteredProducts.sort((a, b) => a.distance - b.distance);

        // Update map
        map.removeLayer(markers);
        markers = L.markerClusterGroup();

        filteredProducts.forEach(product => {
            const marker = L.marker([product.latitude, product.longitude], {
                icon: L.icon({
                    iconUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCAzMiA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTAgMTZDMCA3LjE3NjMgNy4xNzYzIDAgMTYgMEMyNC44MjM3IDAgMzIgNy4xNzYzIDMyIDE2QzMyIDI1IDE2IDQ4IDE2IDQ4UzAgMjUgMCAxNloiIGZpbGw9IiNGRjZCMzUiLz4KPHRleHQgeD0iMTYiIHk9IjIwIiBmb250LXNpemU9IjEyIiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkVhdGluZzwvdGV4dD4KPC9zdmc+',
                    iconSize: [32, 48],
                    iconAnchor: [16, 48],
                })
            });

            const ratingStars = '★'.repeat(Math.round(product.average_rating)) + '☆'.repeat(5 - Math.round(product.average_rating));

            const popupContent = `
                <div class="popup-place-name">${product.name}</div>
                <div class="popup-rating">${ratingStars} ${product.average_rating}/5</div>
                <div class="popup-distance"><i class="fas fa-location-dot me-1"></i>${product.distance.toFixed(2)} km</div>
                <div class="popup-distance"><i class="fas fa-map-marker me-1"></i>${product.address || 'Alamat tidak tersedia'}</div>
                <a href="/produk/${product.slug}" class="popup-btn"><i class="fas fa-info-circle me-1"></i>Lihat Detail</a>
            `;

            marker.bindPopup(popupContent);
            markers.addLayer(marker);

            marker.addEventListener('click', function() {
                highlightResult(product);
            });
        });

        map.addLayer(markers);
        document.getElementById('resultCount').textContent = filteredProducts.length;

        // Update results list
        updateResultsList();
    }

    function updateResultsList() {
        const resultsList = document.getElementById('resultsList');
        if (filteredProducts.length === 0) {
            resultsList.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-search fa-2x mb-2 d-block"></i>
                    <small>Tidak ada tempat makan dalam jangkauan</small>
                </div>
            `;
            return;
        }

        resultsList.innerHTML = filteredProducts.map(product => `
            <div class="result-item" onclick="window.location.href='/produk/${product.slug}'">
                <div class="result-item-name">${product.name}</div>
                <div class="result-item-rating">
                    ${'★'.repeat(Math.round(product.average_rating))}${'☆'.repeat(5 - Math.round(product.average_rating))}
                    ${product.average_rating}/5
                </div>
                <div class="result-item-distance"><i class="fas fa-location-dot me-1"></i>${product.distance?.toFixed(2) || 0} km</div>
            </div>
        `).join('');
    }

    function highlightResult(product) {
        document.querySelectorAll('.result-item').forEach(el => el.classList.remove('active'));
        const matchingResult = Array.from(document.querySelectorAll('.result-item')).find(el => 
            el.textContent.includes(product.name)
        );
        if (matchingResult) {
            matchingResult.classList.add('active');
            matchingResult.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Haversine formula for distance calculation
    function calculateDistance(lat1, lng1, lat2, lng2) {
        const R = 6371; // km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = 
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // Initial render
    updateResults();
});
</script>

<?= $this->endSection() ?>

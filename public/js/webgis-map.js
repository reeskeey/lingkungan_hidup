/**
 * WebGIS Nasional Limbah B3 Medis Fasyankes
 * Leaflet Map Controller & Spatial Visualizer
 */

document.addEventListener('DOMContentLoaded', function () {
    const mapElement = document.getElementById('webgis-map');
    if (!mapElement) return;

    // 1. Inisialisasi Peta Leaflet (Pusat Geografis Indonesia)
    const defaultCenter = [-2.548926, 118.0148634];
    const defaultZoom = 5;

    const map = L.map('webgis-map', {
        center: defaultCenter,
        zoom: defaultZoom,
        zoomControl: true,
        attributionControl: true
    });

    // 2. Definisi Peta Dasar (Base Maps)
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    });

    const cartoPositron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '&copy; CartoDB Positron'
    });

    const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: '&copy; Esri World Imagery'
    });

    // Default base layer
    cartoPositron.addTo(map);

    L.control.layers({
        "Peta Terang (Carto)": cartoPositron,
        "OpenStreetMap": osmLayer,
        "Citra Satelit (Esri)": esriSatellite
    }, null, { position: 'topright' }).addTo(map);

    // 3. Layer Groups
    const fasyankesCluster = L.markerClusterGroup({
        chunkedLoading: true,
        maxClusterRadius: 50,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false
    });

    const treatmentLayer = L.layerGroup();
    const transferLayer = L.layerGroup();
    const bufferLayer = L.layerGroup();
    const choroplethLayer = L.layerGroup();

    map.addLayer(fasyankesCluster);
    map.addLayer(treatmentLayer);
    map.addLayer(transferLayer);
    map.addLayer(bufferLayer);
    map.addLayer(choroplethLayer);

    // 4. Custom Icon Helpers
    function createCustomMarkerIcon(bgColor, iconClass) {
        return L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="
                background-color: ${bgColor};
                width: 30px;
                height: 30px;
                border-radius: 50%;
                border: 2px solid #ffffff;
                box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 14px;
            "><i class="${iconClass}"></i></div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            popupAnchor: [0, -15]
        });
    }

    const iconFasyankes = createCustomMarkerIcon('#dc3545', 'bi bi-hospital');
    const iconTreatment = createCustomMarkerIcon('#e65100', 'bi bi-fire');
    const iconTransfer = createCustomMarkerIcon('#0d47a1', 'bi bi-box-seam');

    // 5. Muat Data Spasial dari Server
    function loadMapData() {
        const provinceId = document.getElementById('filterProvince').value;
        const fasyankesType = document.getElementById('filterType').value;
        const gapStatus = document.getElementById('filterGapStatus').value;

        const url = new URL('/webgis/data', window.location.origin);
        if (provinceId) url.searchParams.set('province_id', provinceId);
        if (fasyankesType) url.searchParams.set('fasyankes_type', fasyankesType);
        if (gapStatus) url.searchParams.set('gap_status', gapStatus);

        // Reset semua layer
        fasyankesCluster.clearLayers();
        treatmentLayer.clearLayers();
        transferLayer.clearLayers();
        bufferLayer.clearLayers();
        choroplethLayer.clearLayers();

        fetch(url)
            .then(res => res.json())
            .then(data => {
                // Update badge hitungan
                document.getElementById('countFasyankes').textContent = data.fasyankes.length;
                document.getElementById('countTreatment').textContent = data.treatment_facilities.length;
                document.getElementById('countTransfer').textContent = data.transfer_locations.length;

                // A. Render Fasyankes
                data.fasyankes.forEach(f => {
                    const marker = L.marker([f.lat, f.lng], { icon: iconFasyankes });
                    const popupHtml = `
                        <div class="p-1">
                            <span class="badge bg-danger mb-1">${f.type}</span>
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">${f.name}</h6>
                            <div class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> ${f.regency}, ${f.province}</div>
                            <table class="table table-sm table-borderless small mb-2">
                                <tr><td>Kapasitas TT:</td><td class="fw-bold">${f.beds} TT</td></tr>
                                <tr><td>Timbulan Medis:</td><td class="fw-bold text-danger">${f.daily_waste_kg} kg/hari</td></tr>
                                <tr><td>Status Izin TPS:</td><td><span class="badge ${f.permit_status === 'Memiliki Izin' ? 'bg-success' : 'bg-secondary'}">${f.permit_status}</span></td></tr>
                            </table>
                        </div>
                    `;
                    marker.bindPopup(popupHtml);
                    fasyankesCluster.addLayer(marker);
                });

                // B. Render Fasilitas Pengolahan & Buffer Radius
                data.treatment_facilities.forEach(t => {
                    const marker = L.marker([t.lat, t.lng], { icon: iconTreatment });
                    const popupHtml = `
                        <div class="p-1">
                            <span class="badge bg-warning text-dark mb-1">${t.type}</span>
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">${t.name}</h6>
                            <div class="text-muted small mb-2"><i class="bi bi-building"></i> ${t.category}</div>
                            <table class="table table-sm table-borderless small mb-2">
                                <tr><td>Kapasitas Izin:</td><td class="fw-bold text-success">${t.licensed_cap_ton_day} ton/hari</td></tr>
                                <tr><td>Kapasitas Terpasang:</td><td>${t.installed_cap_kg_h} kg/jam</td></tr>
                                <tr><td>No. Izin KLH:</td><td class="small font-monospace">${t.permit_number || '-'}</td></tr>
                            </table>
                        </div>
                    `;
                    marker.bindPopup(popupHtml);
                    treatmentLayer.addLayer(marker);

                    // Buffer Radius (50 km & 100 km)
                    const circle50 = L.circle([t.lat, t.lng], {
                        radius: 50000,
                        color: '#2e7d32',
                        fillColor: '#4caf50',
                        fillOpacity: 0.08,
                        weight: 1.5,
                        dashArray: '4, 4'
                    }).bindTooltip(`Jangkauan Layanan 50 km: ${t.name}`);

                    const circle100 = L.circle([t.lat, t.lng], {
                        radius: 100000,
                        color: '#1b5e20',
                        fillColor: '#81c784',
                        fillOpacity: 0.04,
                        weight: 1,
                        dashArray: '6, 6'
                    }).bindTooltip(`Jangkauan Layanan 100 km: ${t.name}`);

                    bufferLayer.addLayer(circle50);
                    bufferLayer.addLayer(circle100);
                });

                // C. Render Lokasi Pemindahan (Depo)
                data.transfer_locations.forEach(loc => {
                    const marker = L.marker([loc.lat, loc.lng], { icon: iconTransfer });
                    const popupHtml = `
                        <div class="p-1">
                            <span class="badge bg-primary mb-1">Lokasi Pemindahan</span>
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">${loc.name}</h6>
                            <div class="text-muted small mb-2"><i class="bi bi-pin-map"></i> ${loc.province}</div>
                            <table class="table table-sm table-borderless small mb-2">
                                <tr><td>Daya Tampung:</td><td class="fw-bold text-primary">${loc.holding_cap_ton} ton</td></tr>
                                <tr><td>Fasilitas Cold Storage:</td><td>${loc.has_cold_storage ? '<span class="badge bg-info text-dark">Tersedia</span>' : '<span class="badge bg-light text-muted border">Tidak Ada</span>'}</td></tr>
                                <tr><td>Target Fasyankes:</td><td>${loc.target_fasyankes} Fasilitas</td></tr>
                            </table>
                        </div>
                    `;
                    marker.bindPopup(popupHtml);
                    transferLayer.addLayer(marker);
                });

                // D. Render Choropleth Provinsi (Visualisasi Lingkaran Gradasi Status Gap)
                data.provinces.forEach(p => {
                    let color = '#2e7d32'; // Surplus
                    if (p.status === 'Defisit Kritis') color = '#c62828';
                    else if (p.status === 'Defisit Sedang') color = '#ef6c00';

                    const provCircle = L.circleMarker([p.lat, p.lng], {
                        radius: 18,
                        fillColor: color,
                        color: '#ffffff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.45
                    });

                    const popupHtml = `
                        <div class="p-1">
                            <div class="fw-bold text-dark mb-1">${p.name}</div>
                            <span class="badge ${p.status === 'Defisit Kritis' ? 'bg-danger' : (p.status === 'Defisit Sedang' ? 'bg-warning text-dark' : 'bg-success')} mb-2">${p.status}</span>
                            <table class="table table-sm table-borderless small mb-0">
                                <tr><td>Timbulan Medis:</td><td class="fw-bold">${p.total_waste} ton/hari</td></tr>
                                <tr><td>Kapasitas Olah:</td><td class="fw-bold">${p.total_capacity} ton/hari</td></tr>
                                <tr><td>Selisih / Gap:</td><td class="fw-bold ${p.gap < 0 ? 'text-danger' : 'text-success'}">${p.gap} ton/hari</td></tr>
                                <tr><td>Rasio Ketercakupan:</td><td class="fw-bold">${p.coverage_ratio}%</td></tr>
                            </table>
                        </div>
                    `;
                    provCircle.bindPopup(popupHtml);
                    provCircle.bindTooltip(`${p.name}: ${p.status} (${p.coverage_ratio}%)`, { direction: 'top' });
                    choroplethLayer.addLayer(provCircle);
                });
            })
            .catch(err => console.error('Error fetching map features:', err));
    }

    // 6. Layer Switch Handlers
    document.getElementById('layerFasyankes').addEventListener('change', function (e) {
        if (e.target.checked) map.addLayer(fasyankesCluster);
        else map.removeLayer(fasyankesCluster);
    });

    document.getElementById('layerTreatment').addEventListener('change', function (e) {
        if (e.target.checked) map.addLayer(treatmentLayer);
        else map.removeLayer(treatmentLayer);
    });

    document.getElementById('layerTransfer').addEventListener('change', function (e) {
        if (e.target.checked) map.addLayer(transferLayer);
        else map.removeLayer(transferLayer);
    });

    document.getElementById('layerBuffer').addEventListener('change', function (e) {
        if (e.target.checked) map.addLayer(bufferLayer);
        else map.removeLayer(bufferLayer);
    });

    document.getElementById('layerChoropleth').addEventListener('change', function (e) {
        if (e.target.checked) map.addLayer(choroplethLayer);
        else map.removeLayer(choroplethLayer);
    });

    // 7. Filter Buttons
    document.getElementById('btnApplyFilter').addEventListener('click', function () {
        const provinceSelect = document.getElementById('filterProvince');
        const selectedOption = provinceSelect.options[provinceSelect.selectedIndex];

        if (selectedOption && selectedOption.value) {
            const lat = parseFloat(selectedOption.getAttribute('data-lat'));
            const lng = parseFloat(selectedOption.getAttribute('data-lng'));
            const zoom = parseInt(selectedOption.getAttribute('data-zoom')) || 9;
            map.flyTo([lat, lng], zoom, { duration: 1.2 });
        }
        loadMapData();
    });

    document.getElementById('btnResetFilter').addEventListener('click', function () {
        document.getElementById('filterProvince').value = '';
        document.getElementById('filterType').value = '';
        document.getElementById('filterGapStatus').value = '';
        map.flyTo(defaultCenter, defaultZoom, { duration: 1.2 });
        loadMapData();
    });

    // Load initial map data
    loadMapData();
});

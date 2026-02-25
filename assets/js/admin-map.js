const TYPE = {
    bridges:  { color: '#6b7280', emoji: '🌉', label: 'Bridges'        },
    normal:   { color: '#10b981', emoji: '✅', label: 'Normal'         },
    warning:  { color: '#f97316', emoji: '⚠️', label: 'Warning'        },
    danger:   { color: '#dc2626', emoji: '❗', label: 'Danger'         },
    critical: { color: '#b91c1c', emoji: '🚨', label: 'Critical'       },
    flooded:  { color: '#3b82f6', emoji: '🌊', label: 'Flooded Areas'  },
    location: { color: '#10b981', emoji: '📍', label: 'Your Location'  },
};

// helper to map legacy types to new categories
function normalizeType(type) {
    if (TYPE[type]) return type;
    const mapping = {
        alert: 'warning',
        evacuation: 'bridges',
        road_closure: 'warning',
        rainfall: 'warning'
    };
    return mapping[type] || 'normal';
}

// Map centered on Bacolod City
const map = L.map('map', { center: [10.6765, 122.9509], zoom: 14 });

// format timestamp into "X ago" text
function formatTimeAgo(ts) {
    const diff = Date.now() - ts;
    const seconds = Math.floor(diff / 1000);
    if (seconds < 60) return `${seconds} second${seconds !== 1 ? 's' : ''} ago`;
    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
    const hours = Math.floor(minutes / 60);
    return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
}

// create or update the location marker and popup
function updateLocationMarker(lat, lng) {
    locationLastUpdated = Date.now();
    const locObj = { title: 'Your Location', type: 'location', description: '', updated_at: locationLastUpdated };
    const content = markerPopupContent(locObj);

    if (locationMarker) {
        locationMarker.setLatLng([lat, lng]);
        locationMarker.setPopupContent(content);
    } else {
        locationMarker = L.marker([lat, lng], { icon: makeIcon('location') }).addTo(map);
        locationMarker.bindPopup(content, { autoPan: false });
        locationMarker.on('click', () => {
            // refresh popup when opened
            locationMarker.getPopup().setContent(markerPopupContent(locObj));
        });
    }
}

function startGeolocation() {
    if (!navigator.geolocation) {
        console.warn('Geolocation not supported');
        return;
    }
    locationWatchId = navigator.geolocation.watchPosition(
        pos => {
            updateLocationMarker(pos.coords.latitude, pos.coords.longitude);
        },
        err => {
            console.error('Location error', err);
        },
        { enableHighAccuracy: true, maximumAge: 60000 }
    );
}

// build popup HTML for a marker, including relative update time
function markerPopupContent(m) {
    const typeInfo = TYPE[ normalizeType(m.type) ] || TYPE.normal;
    const updated = m.updated_at ? formatTimeAgo(new Date(m.updated_at).getTime()) : '';
    return `
        <div style="min-width: 280px; font-family: Arial, sans-serif;">
            <div style="padding: 12px 14px; border-bottom: 3px solid ${typeInfo.color}; margin-bottom: 10px;">
                <h3 style="margin: 0 0 8px 0; font-size: 16px; color: #1f2937;">${m.title}</h3>
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                    <span style="font-size: 18px;">${typeInfo.emoji}</span>
                    <strong style="color: ${typeInfo.color};">${typeInfo.label}</strong>
                </div>
            </div>
            ${m.description ? `<div style="padding: 0 14px 12px 14px; font-size: 13px; color: #4b5563; line-height: 1.5;">${m.description}</div>` : ''}
            ${updated ? `<div style="padding: 0 14px 8px 14px; font-size:12px; color:#6b7280;"><em>Updated: ${updated}</em></div>` : ''}
        </div>
    `;
}

// attempt to refresh the location popup text periodically if it's open
setInterval(() => {
    if (locationMarker && locationMarker.getPopup().isOpen()) {
        const locObj = { title: 'Your Location', type: 'location', description: '', updated_at: locationLastUpdated };
        locationMarker.getPopup().setContent(markerPopupContent(locObj));
    }
}, 30000);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

function makeIcon(type) {
    const t = normalizeType(type);
    const c = TYPE[t];
    return L.divIcon({
        className: '',
        html: `<div class="cm" style="background:${c.color}"><span>${c.emoji}</span></div>`,
        iconSize: [36,36], iconAnchor: [18,36], popupAnchor: [0,-40]
    });
}

// In-memory store
let store      = {};
let nextId     = 1;
let addingType = null;

// user location tracking
let locationMarker = null;
let locationLastUpdated = null;
let locationWatchId = null;

// Load markers from database on page load
function loadMarkersFromDatabase() {
    fetch('../controllers/marker-api.php?action=list')
        .then(res => res.json())
        .then(result => {
            if (result.success && result.markers) {
                // Find the highest ID to continue numbering
                result.markers.forEach(m => {
                    store[m.id] = null; // Mark as loaded
                    if (m.id >= nextId) nextId = m.id + 1;
                });
                
                // Place all markers on the map
                result.markers.forEach(m => {
                    const lm = L.marker([m.lat, m.lng], {
                        icon: makeIcon(m.type),
                        draggable: true
                    }).addTo(map);

                    const labelType = normalizeType(m.type);
                    lm.bindTooltip(
                        `<strong>${m.title}</strong><br><em>${TYPE[labelType].label}</em>`,
                        { direction: 'top', offset: [0,-38] }
                    );

                    lm.bindPopup(markerPopupContent(m), { maxWidth: 300 });

                    lm.on('click', () => openModal(m, lm));
                    lm.on('dragend', () => {
                        const ll = lm.getLatLng();
                        m.lat = ll.lat;
                        m.lng = ll.lng;
                        // update timestamp locally
                        m.updated_at = new Date().toISOString();
                        lm.setPopupContent(markerPopupContent(m));
                        // Update position in database
                        fetch('../controllers/marker-api.php?action=update', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                id: m.id,
                                lat: m.lat,
                                lng: m.lng,
                                title: m.title,
                                description: m.description,
                                type: m.type
                            })
                        }).catch(err => console.error('Failed to update marker position:', err));
                    });

                    store[m.id] = { marker: lm, data: m };
                });
            }
        })
        .catch(err => console.error('Failed to load markers:', err));
}

// Load markers when page loads
document.addEventListener('DOMContentLoaded', () => {
    loadMarkersFromDatabase();
    startGeolocation();
});

function placeMarker(m) {
    // ensure update time exists
    if (!m.updated_at) {
        m.updated_at = new Date().toISOString();
    }

    const lm = L.marker([m.lat, m.lng], {
        icon: makeIcon(m.type),
        draggable: true
    }).addTo(map);

    const labelType = normalizeType(m.type);
    lm.bindTooltip(
        `<strong>${m.title}</strong><br><em>${TYPE[labelType].label}</em>`,
        { direction: 'top', offset: [0,-38] }
    );
    lm.bindPopup(markerPopupContent(m), { maxWidth: 300 });

    lm.on('click',   ()  => openModal(m, lm));
    lm.on('dragend', ()  => {
        const ll = lm.getLatLng();
        m.lat = ll.lat;
        m.lng = ll.lng;
        m.updated_at = new Date().toISOString();
        lm.setPopupContent(markerPopupContent(m));
        // also persist new coords if this is already saved
        if (m.id) {
            fetch('../controllers/marker-api.php?action=update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: m.id,
                    lat: m.lat,
                    lng: m.lng,
                    title: m.title,
                    description: m.description,
                    type: m.type
                })
            }).catch(err => console.error('Failed to update marker position:', err));
        }
    });

    store[m.id] = { marker: lm, data: m };
}

// ── Toolbar ────────────────────────────────────────────────────────────────
document.querySelectorAll('.tool-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        addingType = btn.dataset.type;
        document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('mode-indicator').textContent =
            `Mode: Adding ${TYPE[addingType].label}`;
        document.getElementById('cancel-add').style.display = 'inline-flex';
        map.getContainer().style.cursor = 'crosshair';
    });
});

document.getElementById('cancel-add').addEventListener('click', cancelAdd);

function cancelAdd() {
    addingType = null;
    document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('mode-indicator').textContent = 'Mode: View';
    document.getElementById('cancel-add').style.display  = 'none';
    map.getContainer().style.cursor = '';
}

map.on('click', e => {
    if (!addingType) return;
    const type = addingType;
    cancelAdd();
    openModal({ id: null, title: '', description: '',
        lat: e.latlng.lat, lng: e.latlng.lng, type }, null);
});

// ── Modal ──────────────────────────────────────────────────────────────────
function openModal(data, lm) {
    document.getElementById('marker-id').value          = data.id  || '';
    document.getElementById('marker-lat').value         = data.lat;
    document.getElementById('marker-lng').value         = data.lng;
    document.getElementById('marker-title-input').value = data.title || '';
    document.getElementById('marker-desc-input').value  = data.description || '';
    // convert any legacy type to a current one so the select has a valid choice
    document.getElementById('marker-type-select').value = normalizeType(data.type);
    document.getElementById('modal-title').textContent  = data.id ? 'Edit Marker' : 'Add Marker';
    document.getElementById('modal-delete').style.display = data.id ? 'inline-flex' : 'none';
    document.getElementById('marker-modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('marker-modal').style.display = 'none';
}

document.getElementById('modal-cancel').addEventListener('click', closeModal);

document.getElementById('modal-save').addEventListener('click', () => {
    const title = document.getElementById('marker-title-input').value.trim();
    if (!title) { alert('Please enter a title.'); return; }

    const id   = document.getElementById('marker-id').value;
    const type = document.getElementById('marker-type-select').value;
    const desc = document.getElementById('marker-desc-input').value.trim();
    const lat  = parseFloat(document.getElementById('marker-lat').value);
    const lng  = parseFloat(document.getElementById('marker-lng').value);

    // Prepare data for API
    const markerData = {
        title,
        description: desc,
        type,
        lat,
        lng
    };

    if (id && store[id]) {
        // Update existing marker
        markerData.id = id;
        fetch('../controllers/marker-api.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(markerData)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                const entry = store[id];
                entry.data.title       = title;
                entry.data.description = desc;
                entry.data.type        = type;
                entry.data.updated_at  = new Date().toISOString();
                entry.marker.setIcon(makeIcon(type));
                entry.marker.setTooltipContent(
                    `<strong>${title}</strong><br><em>${TYPE[type]?.label}</em>`);
                entry.marker.setPopupContent(markerPopupContent(entry.data));
            } else {
                alert('Error updating marker: ' + result.message);
            }
            closeModal();
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Failed to update marker');
        });
    } else {
        // Create new marker
        fetch('../controllers/marker-api.php?action=create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(markerData)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                const m = {
                    id: result.id,
                    title,
                    description: desc,
                    lat,
                    lng,
                    type,
                    updated_at: new Date().toISOString()
                };
                placeMarker(m);
            } else {
                alert('Error creating marker: ' + result.message);
            }
            closeModal();
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Failed to create marker');
        });
    }
});

document.getElementById('modal-delete').addEventListener('click', () => {
    const id = document.getElementById('marker-id').value;
    if (!id || !confirm('Delete this marker?')) return;
    
    fetch('../controllers/marker-api.php?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: parseInt(id) })
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            if (store[id]) { 
                map.removeLayer(store[id].marker); 
                delete store[id]; 
            }
        } else {
            alert('Error deleting marker: ' + result.message);
        }
        closeModal();
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Failed to delete marker');
    });
});
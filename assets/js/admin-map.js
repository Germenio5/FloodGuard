const TYPE = {
    danger:       { color: '#dc2626', emoji: '🌊', label: 'Danger Zone'       },
    alert:        { color: '#f97316', emoji: '⚠️',  label: 'Alert Area'        },
    normal:       { color: '#10b981', emoji: '✅',  label: 'Normal'            },
    evacuation:   { color: '#3b82f6', emoji: '🏠',  label: 'Evacuation Center' },
    road_closure: { color: '#6b7280', emoji: '🚫',  label: 'Road Closure'      },
    rainfall:     { color: '#6366f1', emoji: '☔',  label: 'Heavy Rainfall'    },
};

// Map centered on Bacolod City
const map = L.map('map', { center: [10.6765, 122.9509], zoom: 14 });
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

function makeIcon(type) {
    const c = TYPE[type] || TYPE.danger;
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

                    lm.bindTooltip(
                        `<strong>${m.title}</strong><br><em>${TYPE[m.type]?.label}</em>`,
                        { direction: 'top', offset: [0,-38] }
                    );

                    lm.on('click', () => openModal(m, lm));
                    lm.on('dragend', () => {
                        const ll = lm.getLatLng();
                        m.lat = ll.lat;
                        m.lng = ll.lng;
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
document.addEventListener('DOMContentLoaded', loadMarkersFromDatabase);

function placeMarker(m) {
    const lm = L.marker([m.lat, m.lng], {
        icon: makeIcon(m.type),
        draggable: true
    }).addTo(map);

    lm.bindTooltip(
        `<strong>${m.title}</strong><br><em>${TYPE[m.type]?.label}</em>`,
        { direction: 'top', offset: [0,-38] }
    );

    lm.on('click',   ()  => openModal(m, lm));
    lm.on('dragend', ()  => {
        const ll = lm.getLatLng();
        m.lat = ll.lat;
        m.lng = ll.lng;
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
    document.getElementById('marker-type-select').value = data.type;
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
                entry.marker.setIcon(makeIcon(type));
                entry.marker.setTooltipContent(
                    `<strong>${title}</strong><br><em>${TYPE[type]?.label}</em>`);
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
                    type
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
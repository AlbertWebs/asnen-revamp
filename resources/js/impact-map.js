import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

// Fix default marker paths under Vite.
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

const BRAND = '#0C77BC';
const LIME = '#8CC63F';

function brandIcon(featured = false, color = null) {
    const fill = color || (featured ? LIME : BRAND);
    const size = featured ? 22 : 16;
    return L.divIcon({
        className: 'impact-map-marker',
        html: `<span class="impact-map-marker__dot${featured ? ' is-featured' : ''}" style="--marker:${fill};--size:${size}px"></span>`,
        iconSize: [size, size],
        iconAnchor: [size / 2, size / 2],
        popupAnchor: [0, -size / 2],
    });
}

function areaStyle(region, active = false) {
    const color = region.map_color || (region.is_featured ? LIME : BRAND);
    return {
        color,
        weight: active ? 2.5 : 1.5,
        opacity: active ? 0.95 : 0.8,
        fillColor: color,
        fillOpacity: active ? 0.45 : 0.28,
        className: active ? 'impact-map-area is-active' : 'impact-map-area',
    };
}

export function impactRegionsMap(config) {
    return {
        regions: config.regions || [],
        activeId: null,
        map: null,
        markers: {},
        areas: {},
        layer: null,

        init() {
            this.map = L.map(this.$refs.map, {
                scrollWheelZoom: false,
                zoomControl: true,
                attributionControl: true,
            }).setView(config.center || [-0.5, 37.5], config.zoom || 6);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 18,
            }).addTo(this.map);

            this.layer = L.featureGroup().addTo(this.map);

            this.regions.forEach((region) => {
                this.addRegionArea(region);
                this.addRegionMarker(region);
            });

            if (this.layer.getLayers().length) {
                this.map.fitBounds(this.layer.getBounds().pad(0.28), { maxZoom: 8 });
            }

            this.map.on('focus', () => this.map.scrollWheelZoom.enable());
            this.map.on('blur', () => this.map.scrollWheelZoom.disable());
            this.$refs.map.addEventListener('click', () => this.map.scrollWheelZoom.enable());

            setTimeout(() => this.map.invalidateSize(), 80);
        },

        addRegionArea(region) {
            let area = null;

            if (region.boundary) {
                area = L.geoJSON(
                    {
                        type: 'Feature',
                        properties: { id: region.id, name: region.name },
                        geometry: region.boundary,
                    },
                    {
                        style: () => areaStyle(region, false),
                        onEachFeature: (feature, layer) => {
                            layer.bindPopup(this.popupHtml(region), {
                                className: 'impact-map-popup',
                                maxWidth: 280,
                            });
                            layer.on('click', () => {
                                this.activeId = region.id;
                                this.highlightArea(region.id);
                            });
                        },
                    }
                );
            } else if (
                region.latitude != null &&
                region.longitude != null &&
                region.reach_radius_km
            ) {
                area = L.circle([region.latitude, region.longitude], {
                    radius: Number(region.reach_radius_km) * 1000,
                    ...areaStyle(region, false),
                });
                area.bindPopup(this.popupHtml(region), {
                    className: 'impact-map-popup',
                    maxWidth: 280,
                });
                area.on('click', () => {
                    this.activeId = region.id;
                    this.highlightArea(region.id);
                });
            }

            if (!area) return;

            area.addTo(this.layer);
            this.areas[region.id] = area;
        },

        addRegionMarker(region) {
            if (region.latitude == null || region.longitude == null) return;

            const marker = L.marker([region.latitude, region.longitude], {
                icon: brandIcon(region.is_featured, region.map_color),
                title: region.name,
                zIndexOffset: region.is_featured ? 400 : 200,
            });

            marker.bindPopup(this.popupHtml(region), { className: 'impact-map-popup', maxWidth: 280 });
            marker.on('click', () => {
                this.activeId = region.id;
                this.highlightArea(region.id);
            });
            marker.addTo(this.layer);
            this.markers[region.id] = marker;
        },

        highlightArea(regionId) {
            Object.entries(this.areas).forEach(([id, layer]) => {
                const region = this.regions.find((item) => String(item.id) === String(id));
                if (!region) return;
                const active = String(id) === String(regionId);
                if (typeof layer.setStyle === 'function') {
                    layer.setStyle(areaStyle(region, active));
                } else if (typeof layer.eachLayer === 'function') {
                    layer.eachLayer((child) => child.setStyle(areaStyle(region, active)));
                }
                if (active && typeof layer.bringToFront === 'function') {
                    layer.bringToFront();
                }
            });
        },

        popupHtml(region) {
            const label = region.impact_label
                ? `<p class="impact-map-popup__label">${this.escape(region.impact_label)}</p>`
                : '';
            const desc = region.description
                ? `<p class="impact-map-popup__desc">${this.escape(region.description)}</p>`
                : '';
            const link = region.link_url
                ? `<a class="impact-map-popup__link" href="${this.escape(region.link_url)}">${this.escape(region.link_label || 'Learn more')}</a>`
                : '';
            return `
                <div class="impact-map-popup__inner">
                    <h3 class="impact-map-popup__title">${this.escape(region.name)}</h3>
                    ${label}
                    ${desc}
                    ${link}
                </div>
            `;
        },

        escape(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#39;');
        },

        selectRegion(region) {
            this.activeId = region.id;
            this.highlightArea(region.id);

            const area = this.areas[region.id];
            const marker = this.markers[region.id];

            if (area && this.map) {
                const bounds = typeof area.getBounds === 'function' ? area.getBounds() : null;
                if (bounds && bounds.isValid()) {
                    this.map.flyToBounds(bounds.pad(0.2), { maxZoom: 9, duration: 0.7 });
                } else if (region.latitude != null && region.longitude != null) {
                    this.map.flyTo([region.latitude, region.longitude], Math.max(this.map.getZoom(), 8), {
                        duration: 0.7,
                    });
                }
                if (typeof area.openPopup === 'function') {
                    area.openPopup();
                } else if (typeof area.eachLayer === 'function') {
                    const first = area.getLayers()[0];
                    first?.openPopup?.();
                }
                return;
            }

            if (marker && this.map && region.latitude != null && region.longitude != null) {
                this.map.flyTo([region.latitude, region.longitude], Math.max(this.map.getZoom(), 8), {
                    duration: 0.7,
                });
                marker.openPopup();
            }
        },
    };
}

export function regionMapPicker(config) {
    return {
        lat: config.lat,
        lng: config.lng,
        map: null,
        marker: null,

        init() {
            const startLat = this.lat ?? -1.286389;
            const startLng = this.lng ?? 36.817223;
            const zoom = this.lat != null && this.lng != null ? 8 : 6;

            this.map = L.map(this.$refs.map, {
                scrollWheelZoom: false,
            }).setView([startLat, startLng], zoom);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap &copy; CARTO',
                subdomains: 'abcd',
                maxZoom: 18,
            }).addTo(this.map);

            if (this.lat != null && this.lng != null) {
                this.placeMarker(this.lat, this.lng);
            }

            this.map.on('click', (event) => {
                this.lat = Number(event.latlng.lat.toFixed(6));
                this.lng = Number(event.latlng.lng.toFixed(6));
                this.placeMarker(this.lat, this.lng);
            });

            setTimeout(() => this.map.invalidateSize(), 80);
        },

        placeMarker(lat, lng) {
            if (this.marker) {
                this.marker.setLatLng([lat, lng]);
                return;
            }
            this.marker = L.marker([lat, lng], {
                draggable: true,
                icon: brandIcon(true),
            }).addTo(this.map);
            this.marker.on('dragend', () => {
                const pos = this.marker.getLatLng();
                this.lat = Number(pos.lat.toFixed(6));
                this.lng = Number(pos.lng.toFixed(6));
            });
        },

        syncFromInputs() {
            if (this.lat === '' || this.lat == null || this.lng === '' || this.lng == null) {
                return;
            }
            const lat = Number(this.lat);
            const lng = Number(this.lng);
            if (Number.isNaN(lat) || Number.isNaN(lng)) return;
            this.placeMarker(lat, lng);
            this.map.setView([lat, lng], Math.max(this.map.getZoom(), 8));
        },
    };
}

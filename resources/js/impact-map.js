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

function brandIcon(featured = false) {
    const color = featured ? LIME : BRAND;
    const size = featured ? 22 : 16;
    return L.divIcon({
        className: 'impact-map-marker',
        html: `<span class="impact-map-marker__dot${featured ? ' is-featured' : ''}" style="--marker:${color};--size:${size}px"></span>`,
        iconSize: [size, size],
        iconAnchor: [size / 2, size / 2],
        popupAnchor: [0, -size / 2],
    });
}

export function impactRegionsMap(config) {
    return {
        regions: config.regions || [],
        activeId: null,
        map: null,
        markers: {},
        layer: null,

        init() {
            const mappable = this.regions.filter((r) => r.latitude != null && r.longitude != null);
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

            mappable.forEach((region) => {
                const marker = L.marker([region.latitude, region.longitude], {
                    icon: brandIcon(region.is_featured),
                    title: region.name,
                });

                marker.bindPopup(this.popupHtml(region), { className: 'impact-map-popup', maxWidth: 280 });
                marker.on('click', () => {
                    this.activeId = region.id;
                });
                marker.addTo(this.layer);
                this.markers[region.id] = marker;
            });

            if (mappable.length) {
                this.map.fitBounds(this.layer.getBounds().pad(0.35), { maxZoom: 8 });
            }

            // Allow wheel zoom only after focus/interaction.
            this.map.on('focus', () => this.map.scrollWheelZoom.enable());
            this.map.on('blur', () => this.map.scrollWheelZoom.disable());
            this.$refs.map.addEventListener('click', () => this.map.scrollWheelZoom.enable());

            setTimeout(() => this.map.invalidateSize(), 80);
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
            const marker = this.markers[region.id];
            if (!marker || !this.map) return;
            this.map.flyTo([region.latitude, region.longitude], Math.max(this.map.getZoom(), 8), { duration: 0.7 });
            marker.openPopup();
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

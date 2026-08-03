import './bootstrap';

import Alpine from 'alpinejs';
import { accessibilityToolbar } from './accessibility';
import { galleryDropzone } from './gallery-dropzone';
import { partnerLogosDropzone } from './partner-logos-dropzone';
import { impactRegionsMap, regionMapPicker } from './impact-map';
import { initSiteForms } from './site-forms';

window.Alpine = Alpine;
Alpine.data('accessibilityToolbar', accessibilityToolbar);
Alpine.data('galleryDropzone', galleryDropzone);
Alpine.data('partnerLogosDropzone', partnerLogosDropzone);
Alpine.data('impactRegionsMap', impactRegionsMap);
Alpine.data('regionMapPicker', regionMapPicker);
Alpine.start();

function initReveal() {
    const nodes = document.querySelectorAll('.reveal');
    if (!nodes.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        nodes.forEach((el) => el.classList.add('in'));
        return;
    }

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    io.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15 }
    );
    nodes.forEach((el) => io.observe(el));
}

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initSiteForms();
});

import './bootstrap';

import Alpine from 'alpinejs';
import { accessibilityToolbar } from './accessibility';
import { galleryDropzone } from './gallery-dropzone';
import { partnerLogosDropzone } from './partner-logos-dropzone';
import { impactRegionsMap, regionMapPicker } from './impact-map';
import { initSiteForms } from './site-forms';

import { impactCounters } from './impact-counters';
import { heroCarousel } from './hero-carousel';
import { initAdminRichEditors } from './admin-rich-editor';

window.Alpine = Alpine;
Alpine.data('accessibilityToolbar', accessibilityToolbar);
Alpine.data('galleryDropzone', galleryDropzone);
Alpine.data('partnerLogosDropzone', partnerLogosDropzone);
Alpine.data('impactRegionsMap', impactRegionsMap);
Alpine.data('regionMapPicker', regionMapPicker);
Alpine.data('impactCounters', impactCounters);
Alpine.data('heroCarousel', heroCarousel);
Alpine.start();

function syncSiteChromeHeight() {
    const announcement = document.querySelector('[aria-label="Site announcement"]');
    const header = document.getElementById('site-header');
    if (!header && !announcement) return;

    const height = (announcement?.offsetHeight || 0) + (header?.offsetHeight || 0);
    document.documentElement.style.setProperty('--site-chrome', `${height}px`);
}

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
    syncSiteChromeHeight();
    window.addEventListener('resize', syncSiteChromeHeight);
    initReveal();
    initSiteForms();
    initAdminRichEditors();
});

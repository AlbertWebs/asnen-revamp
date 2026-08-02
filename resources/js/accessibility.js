/**
 * ASNEN accessibility preferences - persisted and applied site-wide.
 */
const STORAGE_KEY = 'asnen_a11y_prefs_v1';

const DEFAULTS = {
    text: 'md', // md | lg | xl | 2xl
    spacing: 'normal', // normal | relaxed | loose
    contrast: 'default', // default | high | dark
    readableFont: false,
    underlineLinks: false,
    focusStrong: true,
    highlightHeadings: false,
    reduceMotion: false,
    largeTargets: false,
    bigCursor: false,
    grayscale: false,
    lowSaturation: false,
    hideImages: false,
    readableWidth: false,
    textLeft: false,
    pauseMedia: false,
};

const CLASS_MAP = {
    text: {
        md: null,
        lg: 'a11y-text-lg',
        xl: 'a11y-text-xl',
        '2xl': 'a11y-text-2xl',
    },
    spacing: {
        normal: null,
        relaxed: 'a11y-spacing-relaxed',
        loose: 'a11y-spacing-loose',
    },
    contrast: {
        default: null,
        high: 'a11y-contrast-high',
        dark: 'a11y-contrast-dark',
    },
};

const BOOL_CLASSES = {
    readableFont: 'a11y-readable-font',
    underlineLinks: 'a11y-underline-links',
    focusStrong: 'a11y-focus-strong',
    highlightHeadings: 'a11y-highlight-headings',
    reduceMotion: 'a11y-reduce-motion',
    largeTargets: 'a11y-large-targets',
    bigCursor: 'a11y-big-cursor',
    grayscale: 'a11y-grayscale',
    lowSaturation: 'a11y-low-saturation',
    hideImages: 'a11y-hide-images',
    readableWidth: 'a11y-readable-width',
    textLeft: 'a11y-text-left',
    pauseMedia: 'a11y-pause-media',
};

export function loadPrefs() {
    try {
        return { ...DEFAULTS, ...JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}') };
    } catch {
        return { ...DEFAULTS };
    }
}

export function savePrefs(prefs) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(prefs));
}

export function applyPrefs(prefs, root = document.documentElement) {
    const all = [
        'a11y-text-lg', 'a11y-text-xl', 'a11y-text-2xl',
        'a11y-spacing-relaxed', 'a11y-spacing-loose',
        'a11y-contrast-high', 'a11y-contrast-dark',
        ...Object.values(BOOL_CLASSES),
    ];
    root.classList.remove(...all);

    const textClass = CLASS_MAP.text[prefs.text];
    if (textClass) root.classList.add(textClass);

    const spacingClass = CLASS_MAP.spacing[prefs.spacing];
    if (spacingClass) root.classList.add(spacingClass);

    const contrastClass = CLASS_MAP.contrast[prefs.contrast];
    if (contrastClass) root.classList.add(contrastClass);

    Object.entries(BOOL_CLASSES).forEach(([key, className]) => {
        if (prefs[key]) root.classList.add(className);
    });

    // Honour OS reduced-motion unless user explicitly toggled off after enabling
    if (!prefs.reduceMotion && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        root.classList.add('a11y-reduce-motion');
    }
}

export function announce(message) {
    const region = document.getElementById('a11y-live-region');
    if (!region) return;
    region.textContent = '';
    window.setTimeout(() => {
        region.textContent = message;
    }, 50);
}

export function accessibilityToolbar() {
    return {
        open: false,
        prefs: loadPrefs(),
        init() {
            applyPrefs(this.prefs);
            // Alt + 0 opens accessibility panel
            window.addEventListener('keydown', (e) => {
                if (e.altKey && (e.key === '0' || e.code === 'Digit0')) {
                    e.preventDefault();
                    this.open = true;
                    this.$nextTick(() => this.$refs.panel?.focus());
                    announce('Accessibility preferences panel opened');
                }
                if (e.key === 'Escape' && this.open) {
                    this.open = false;
                    this.$refs.fab?.focus();
                }
            });
        },
        persist(message) {
            savePrefs(this.prefs);
            applyPrefs(this.prefs);
            if (message) announce(message);
        },
        setText(size) {
            this.prefs.text = size;
            this.persist(`Text size set to ${size}`);
        },
        setSpacing(level) {
            this.prefs.spacing = level;
            this.persist(`Text spacing set to ${level}`);
        },
        setContrast(mode) {
            this.prefs.contrast = mode;
            this.persist(`Contrast mode set to ${mode}`);
        },
        toggle(key, label) {
            this.prefs[key] = !this.prefs[key];
            this.persist(`${label} ${this.prefs[key] ? 'on' : 'off'}`);
        },
        reset() {
            this.prefs = { ...DEFAULTS };
            this.persist('Accessibility preferences reset to defaults');
        },
    };
}

// Apply before Alpine mounts when imported
applyPrefs(loadPrefs());

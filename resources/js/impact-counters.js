/**
 * Count-up animation for homepage impact stats.
 * Respects prefers-reduced-motion and a11y-reduce-motion.
 */
export function impactCounters() {
    return {
        started: false,
        observe(root) {
            if (this.shouldSkipMotion()) {
                this.snapToTargets(root);
                return;
            }

            const io = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting && !this.started) {
                            this.started = true;
                            this.animate(root);
                            io.disconnect();
                        }
                    });
                },
                { threshold: 0.35 }
            );

            io.observe(root);
        },
        shouldSkipMotion() {
            return (
                window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
                document.documentElement.classList.contains('a11y-reduce-motion')
            );
        },
        snapToTargets(root) {
            root.querySelectorAll('[data-count-target]').forEach((el) => {
                const target = Number(el.dataset.countTarget || 0);
                if (!target) return;
                const display = el.querySelector('[data-count-display]');
                if (!display) return;
                display.textContent = this.format(
                    target,
                    el.dataset.countPrefix || '',
                    el.dataset.countSuffix || ''
                );
            });
        },
        animate(root) {
            const duration = 1400;
            const nodes = [...root.querySelectorAll('[data-count-target]')];

            nodes.forEach((el, index) => {
                const target = Number(el.dataset.countTarget || 0);
                if (!target) return;
                const display = el.querySelector('[data-count-display]');
                if (!display) return;

                const prefix = el.dataset.countPrefix || '';
                const suffix = el.dataset.countSuffix || '';
                const delay = index * 90;
                const start = performance.now() + delay;

                const tick = (now) => {
                    if (now < start) {
                        requestAnimationFrame(tick);
                        return;
                    }

                    const progress = Math.min(1, (now - start) / duration);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const value = Math.round(target * eased);
                    display.textContent = this.format(value, prefix, suffix);

                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        display.textContent = this.format(target, prefix, suffix);
                    }
                };

                requestAnimationFrame(tick);
            });
        },
        format(value, prefix, suffix) {
            return `${prefix}${Number(value).toLocaleString('en-US')}${suffix}`;
        },
    };
}

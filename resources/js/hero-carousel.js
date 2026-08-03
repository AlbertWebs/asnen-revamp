/**
 * Full-bleed hero image carousel.
 * Autoplay pauses on hover/focus and respects reduced-motion prefs.
 * Progress bar fills over each slide interval.
 */
export function heroCarousel(slideCount = 1) {
    return {
        index: 0,
        count: Math.max(1, Number(slideCount) || 1),
        raf: null,
        paused: false,
        intervalMs: 6000,
        progress: 0,
        startedAt: 0,
        elapsedBeforePause: 0,

        init() {
            if (this.count < 2) return;
            if (this.shouldSkipMotion()) {
                this.progress = 0;
                return;
            }
            this.start();

            this.$watch('paused', (value) => {
                if (value) this.pause();
                else this.resume();
            });
        },

        destroy() {
            this.clearTimer();
        },

        shouldSkipMotion() {
            return (
                window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
                document.documentElement.classList.contains('a11y-reduce-motion')
            );
        },

        clearTimer() {
            if (this.raf) {
                window.cancelAnimationFrame(this.raf);
                this.raf = null;
            }
        },

        start() {
            this.clearTimer();
            this.elapsedBeforePause = 0;
            this.progress = 0;
            if (this.count < 2 || this.paused || this.shouldSkipMotion()) return;
            this.startedAt = performance.now();
            this.tick();
        },

        pause() {
            if (!this.raf) return;
            this.elapsedBeforePause = Math.min(
                this.intervalMs,
                performance.now() - this.startedAt
            );
            this.clearTimer();
        },

        resume() {
            if (this.count < 2 || this.shouldSkipMotion()) return;
            this.clearTimer();
            this.startedAt = performance.now() - this.elapsedBeforePause;
            this.tick();
        },

        tick() {
            const now = performance.now();
            const elapsed = now - this.startedAt;
            this.progress = Math.min(100, (elapsed / this.intervalMs) * 100);

            if (elapsed >= this.intervalMs) {
                this.elapsedBeforePause = 0;
                this.progress = 0;
                this.index = (this.index + 1) % this.count;
                this.startedAt = performance.now();
            }

            this.raf = window.requestAnimationFrame(() => this.tick());
        },

        next() {
            this.index = (this.index + 1) % this.count;
            this.resetProgress();
        },

        prev() {
            this.index = (this.index - 1 + this.count) % this.count;
            this.resetProgress();
        },

        go(i) {
            this.index = ((i % this.count) + this.count) % this.count;
            this.resetProgress();
        },

        resetProgress() {
            this.elapsedBeforePause = 0;
            this.progress = 0;
            if (this.paused || this.shouldSkipMotion()) {
                this.clearTimer();
                return;
            }
            this.start();
        },
    };
}

/**
 * Cascading three-card carousel.
 * Cards keep their photos while they shuffle positions (CSS transitions).
 * After the move settles, only the new back card quietly receives the next photo.
 * Autoplay uses the same path as dots and waits for each shuffle to finish.
 */
export function cascadeCarousel(slides = []) {
    const list = Array.isArray(slides) ? slides : [];
    const wait = (ms) => new Promise((resolve) => window.setTimeout(resolve, ms));

    return {
        slides: list,
        index: 0,
        deck: list.slice(0, Math.min(3, list.length)).map((_, i) => ({
            key: i,
            slide: i,
            slot: i === 0 ? 'front' : i === 1 ? 'mid' : 'back',
        })),
        lightbox: false,
        lightboxIndex: 0,
        paused: false,
        busy: false,
        shuffling: false,
        timer: null,
        intervalMs: 4500,
        shuffleMs: 700,

        get count() {
            return this.slides.length;
        },

        get current() {
            return this.slides[this.lightboxIndex] || null;
        },

        init() {
            if (this.count === 0) return;

            if (this.deck.length < 3 && this.count > 0) {
                const filled = [];
                for (let i = 0; i < 3; i++) {
                    filled.push({
                        key: i,
                        slide: i % this.count,
                        slot: i === 0 ? 'front' : i === 1 ? 'mid' : 'back',
                    });
                }
                this.deck = filled;
            }

            if (this.count < 2) return;
            if (this.shouldSkipMotion()) return;
            this.start();

            this.$watch('paused', (value) => {
                if (value || this.lightbox) this.stop();
                else this.start();
            });

            this.$watch('lightbox', (value) => {
                if (value) this.stop();
                else if (! this.paused && ! this.shouldSkipMotion()) this.start();
            });
        },

        destroy() {
            this.stop();
        },

        shouldSkipMotion() {
            return (
                window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
                document.documentElement.classList.contains('a11y-reduce-motion')
            );
        },

        cardForSlot(slot) {
            return this.deck.find((card) => card.slot === slot) || null;
        },

        slideForCard(card) {
            if (! card || ! this.count) return null;
            return this.slides[card.slide] || null;
        },

        canAutoplay() {
            return (
                this.count >= 2 &&
                ! this.paused &&
                ! this.lightbox &&
                ! this.shouldSkipMotion()
            );
        },

        start() {
            this.stop();
            if (! this.canAutoplay()) return;

            this.timer = window.setTimeout(async () => {
                this.timer = null;
                if (! this.canAutoplay()) return;
                await this.shuffle(1);
                if (this.canAutoplay()) this.start();
            }, this.intervalMs);
        },

        stop() {
            if (this.timer) {
                window.clearTimeout(this.timer);
                this.timer = null;
            }
        },

        async next() {
            this.stop();
            await this.shuffle(1);
            if (this.canAutoplay()) this.start();
        },

        async prev() {
            this.stop();
            await this.shuffle(-1);
            if (this.canAutoplay()) this.start();
        },

        async go(target) {
            if (! this.count || this.busy) return;
            const nextIndex = ((target % this.count) + this.count) % this.count;
            if (nextIndex === this.index) return;

            this.stop();

            // Step one card at a time so dots feel like the original smooth shuffle.
            const forward = (nextIndex - this.index + this.count) % this.count;
            const backward = (this.index - nextIndex + this.count) % this.count;
            const direction = forward <= backward ? 1 : -1;
            const steps = Math.min(forward, backward);

            for (let i = 0; i < steps; i++) {
                await this.shuffle(direction);
            }

            if (this.canAutoplay()) this.start();
        },

        async shuffle(direction = 1) {
            if (! this.count || this.busy) return false;

            if (this.shouldSkipMotion()) {
                this.applyInstant(direction);
                return true;
            }

            const front = this.cardForSlot('front');
            const mid = this.cardForSlot('mid');
            const back = this.cardForSlot('back');
            if (! front || ! mid || ! back) return false;

            this.busy = true;
            this.shuffling = true;

            const to = (this.index + direction + this.count) % this.count;
            const incomingBack = (to + 2) % this.count;
            const incomingSrc = this.slides[incomingBack]?.src;
            if (incomingSrc) {
                const preload = new Image();
                preload.src = incomingSrc;
            }

            try {
                if (direction >= 0) {
                    // Front deals to the back; mid and back step forward.
                    front.slot = 'back';
                    mid.slot = 'front';
                    back.slot = 'mid';
                } else {
                    back.slot = 'front';
                    front.slot = 'mid';
                    mid.slot = 'back';
                }

                this.index = to;
                await wait(this.shuffleMs);

                // After the cards have settled, only fix the photo that newly landed in back.
                // Front/mid already carry the correct photos from the previous stack.
                const settledBack = this.cardForSlot('back');
                if (settledBack) {
                    settledBack.slide = incomingBack;
                }

                return true;
            } finally {
                this.shuffling = false;
                this.busy = false;
            }
        },

        applyInstant(direction) {
            const to = (this.index + direction + this.count) % this.count;
            this.index = to;

            if (direction >= 0) {
                const front = this.cardForSlot('front');
                const mid = this.cardForSlot('mid');
                const back = this.cardForSlot('back');
                if (! front || ! mid || ! back) return;
                front.slot = 'back';
                mid.slot = 'front';
                back.slot = 'mid';
            } else {
                const front = this.cardForSlot('front');
                const mid = this.cardForSlot('mid');
                const back = this.cardForSlot('back');
                if (! front || ! mid || ! back) return;
                back.slot = 'front';
                front.slot = 'mid';
                mid.slot = 'back';
            }

            const settledBack = this.cardForSlot('back');
            if (settledBack) settledBack.slide = (to + 2) % this.count;
        },

        openCard(card) {
            if (! card || this.busy) return;
            this.lightboxIndex = card.slide;
            this.lightbox = true;
            document.documentElement.classList.add('overflow-hidden');
            this.stop();
        },

        closeLightbox() {
            this.lightbox = false;
            document.documentElement.classList.remove('overflow-hidden');
            if (! this.paused && ! this.shouldSkipMotion()) this.start();
        },

        lightboxNext() {
            if (! this.count) return;
            this.lightboxIndex = (this.lightboxIndex + 1) % this.count;
        },

        lightboxPrev() {
            if (! this.count) return;
            this.lightboxIndex = (this.lightboxIndex - 1 + this.count) % this.count;
        },
    };
}

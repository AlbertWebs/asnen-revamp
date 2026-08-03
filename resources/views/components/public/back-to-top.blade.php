{{-- Fixed back-to-top control --}}
<div
    class="back-to-top"
    x-data="{
        visible: false,
        onScroll() {
            this.visible = window.scrollY > 400;
        },
        goTop() {
            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches
                || document.documentElement.classList.contains('a11y-reduce-motion');
            window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
        }
    }"
    x-init="onScroll()"
    @scroll.window="onScroll()"
    x-cloak
>
    <button
        type="button"
        class="back-to-top__btn"
        x-show="visible"
        x-transition.opacity.duration.200ms
        @click="goTop()"
        aria-label="Move up to top of page"
        title="Move up"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        </svg>
    </button>
</div>

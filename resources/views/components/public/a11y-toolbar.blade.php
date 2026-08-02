{{-- Persistent site-wide accessibility toolbar --}}
<div
    class="a11y-fab"
    x-data="accessibilityToolbar()"
    x-cloak
    @keydown.escape.window="if (open) { open = false; $refs.fab.focus() }"
>
    <div id="a11y-live-region" class="sr-only" role="status" aria-live="polite" aria-atomic="true"></div>

    <button
        type="button"
        x-ref="fab"
        @click="open = !open"
        class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-brand text-white shadow-lg ring-2 ring-gold hover:bg-brand-700 focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-2 focus-visible:outline-gold"
        :aria-expanded="open.toString()"
        aria-controls="a11y-preferences-panel"
        aria-haspopup="dialog"
        aria-label="Accessibility preferences"
        title="Accessibility preferences"
    >
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12 2a3 3 0 110 6 3 3 0 010-6zm-1 7h2v4h4v2h-4v7h-2v-7H7v-2h4V9z"/>
        </svg>
    </button>

    <div
        id="a11y-preferences-panel"
        x-ref="panel"
        x-show="open"
        x-transition
        role="dialog"
        aria-modal="false"
        aria-labelledby="a11y-panel-title"
        tabindex="-1"
        class="a11y-panel absolute bottom-16 left-0 w-[min(100vw-2rem,22rem)] rounded-xl border border-charcoal/15 bg-white p-4 text-charcoal shadow-2xl"
    >
        <div class="mb-3 flex items-start justify-between gap-3">
            <div>
                <h2 id="a11y-panel-title" class="text-lg font-bold text-brand">Accessibility preferences</h2>
                <p class="mt-1 text-xs text-charcoal/70">Settings apply across the whole ASNEN website and are saved on this device. Shortcut: Alt+0</p>
            </div>
            <button type="button" class="rounded-md p-2 hover:bg-sand" @click="open = false" aria-label="Close accessibility preferences">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="space-y-4 text-sm">
            <fieldset>
                <legend class="mb-2 font-semibold">Text size</legend>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="size in ['md','lg','xl','2xl']" :key="size">
                        <button type="button" class="a11y-option justify-center" :aria-pressed="(prefs.text === size).toString()" @click="setText(size)">
                            <span x-text="size === 'md' ? 'A' : size === 'lg' ? 'A+' : size === 'xl' ? 'A++' : 'A+++'"></span>
                        </button>
                    </template>
                </div>
            </fieldset>

            <fieldset>
                <legend class="mb-2 font-semibold">Text spacing</legend>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" class="a11y-option justify-center" :aria-pressed="(prefs.spacing === 'normal').toString()" @click="setSpacing('normal')">Default</button>
                    <button type="button" class="a11y-option justify-center" :aria-pressed="(prefs.spacing === 'relaxed').toString()" @click="setSpacing('relaxed')">Relaxed</button>
                    <button type="button" class="a11y-option justify-center" :aria-pressed="(prefs.spacing === 'loose').toString()" @click="setSpacing('loose')">Loose</button>
                </div>
            </fieldset>

            <fieldset>
                <legend class="mb-2 font-semibold">Contrast</legend>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" class="a11y-option justify-center" :aria-pressed="(prefs.contrast === 'default').toString()" @click="setContrast('default')">Default</button>
                    <button type="button" class="a11y-option justify-center" :aria-pressed="(prefs.contrast === 'high').toString()" @click="setContrast('high')">High</button>
                    <button type="button" class="a11y-option justify-center" :aria-pressed="(prefs.contrast === 'dark').toString()" @click="setContrast('dark')">Dark</button>
                </div>
            </fieldset>

            <fieldset>
                <legend class="mb-2 font-semibold">Reading &amp; vision</legend>
                <div class="space-y-2">
                    <button type="button" class="a11y-option" :aria-pressed="prefs.readableFont.toString()" @click="toggle('readableFont', 'Readable font')">
                        <span class="font-semibold">Readable font</span>
                        <span class="block text-xs text-charcoal/70">Atkinson Hyperlegible for clearer letter shapes</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.underlineLinks.toString()" @click="toggle('underlineLinks', 'Underline links')">
                        <span class="font-semibold">Underline links</span>
                        <span class="block text-xs text-charcoal/70">Do not rely on colour alone</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.focusStrong.toString()" @click="toggle('focusStrong', 'Strong focus')">
                        <span class="font-semibold">Strong keyboard focus</span>
                        <span class="block text-xs text-charcoal/70">High-visibility focus ring</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.highlightHeadings.toString()" @click="toggle('highlightHeadings', 'Highlight headings')">
                        <span class="font-semibold">Highlight headings</span>
                        <span class="block text-xs text-charcoal/70">Yellow markers for structure</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.readableWidth.toString()" @click="toggle('readableWidth', 'Readable width')">
                        <span class="font-semibold">Shorter line length</span>
                        <span class="block text-xs text-charcoal/70">Easier reading for many users</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.textLeft.toString()" @click="toggle('textLeft', 'Left-aligned text')">
                        <span class="font-semibold">Left-align text</span>
                    </button>
                </div>
            </fieldset>

            <fieldset>
                <legend class="mb-2 font-semibold">Motion, colour &amp; motor</legend>
                <div class="space-y-2">
                    <button type="button" class="a11y-option" :aria-pressed="prefs.reduceMotion.toString()" @click="toggle('reduceMotion', 'Reduce motion')">
                        <span class="font-semibold">Reduce motion</span>
                        <span class="block text-xs text-charcoal/70">Stops animations and transitions</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.pauseMedia.toString()" @click="toggle('pauseMedia', 'Pause media')">
                        <span class="font-semibold">Pause moving media</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.largeTargets.toString()" @click="toggle('largeTargets', 'Large targets')">
                        <span class="font-semibold">Larger click / tap targets</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.bigCursor.toString()" @click="toggle('bigCursor', 'Big cursor')">
                        <span class="font-semibold">Larger cursor</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.lowSaturation.toString()" @click="toggle('lowSaturation', 'Low saturation')">
                        <span class="font-semibold">Low colour saturation</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.grayscale.toString()" @click="toggle('grayscale', 'Grayscale')">
                        <span class="font-semibold">Grayscale</span>
                    </button>
                    <button type="button" class="a11y-option" :aria-pressed="prefs.hideImages.toString()" @click="toggle('hideImages', 'Reduce images')">
                        <span class="font-semibold">Reduce images</span>
                        <span class="block text-xs text-charcoal/70">Lowers visual load; alt text remains available to assistive tech</span>
                    </button>
                </div>
            </fieldset>

            <div class="flex flex-col gap-2 border-t border-sand pt-3">
                <button type="button" class="rounded-md border border-charcoal/20 px-3 py-2 font-semibold hover:bg-sand" @click="reset()">
                    Reset all preferences
                </button>
                <a href="{{ url('/accessibility') }}" class="text-center text-xs font-semibold text-brand underline underline-offset-2">
                    Read our accessibility statement
                </a>
            </div>
        </div>
    </div>
</div>

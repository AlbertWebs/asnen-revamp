{{-- Privacy-aware cookie preference banner (Alpine). No non-essential scripts load until accepted. --}}
<div
    x-data="{
        open: false,
        init() {
            try {
                if (!localStorage.getItem('asnen_cookie_prefs')) {
                    this.open = true;
                }
            } catch (e) {
                this.open = true;
            }
        },
        accept() {
            try {
                localStorage.setItem('asnen_cookie_prefs', JSON.stringify({ necessary: true, analytics: true, at: Date.now() }));
            } catch (e) {}
            this.open = false;
        },
        reject() {
            try {
                localStorage.setItem('asnen_cookie_prefs', JSON.stringify({ necessary: true, analytics: false, at: Date.now() }));
            } catch (e) {}
            this.open = false;
        }
    }"
    x-show="open"
    x-cloak
    role="dialog"
    aria-labelledby="cookie-consent-title"
    aria-describedby="cookie-consent-desc"
    class="fixed inset-x-0 bottom-0 z-50 border-t border-sand bg-ivory p-4 shadow-lg"
>
    <div class="mx-auto flex max-w-7xl flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="max-w-2xl">
            <h2 id="cookie-consent-title" class="font-display text-lg font-semibold text-forest">Cookie preferences</h2>
            <p id="cookie-consent-desc" class="mt-1 text-sm text-charcoal/80">
                We use necessary cookies to run this site. Optional analytics cookies help us improve the experience and are only used if you accept.
                Read our <a href="{{ url('/cookies') }}" class="underline text-forest hover:text-teal">cookie policy</a>.
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" @click="reject()" class="rounded-md border border-charcoal/20 px-4 py-2 text-sm font-medium text-charcoal hover:bg-sand">
                Necessary only
            </button>
            <button type="button" @click="accept()" class="rounded-md bg-forest px-4 py-2 text-sm font-semibold text-ivory hover:bg-teal">
                Accept all
            </button>
        </div>
    </div>
</div>

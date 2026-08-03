<x-guest-layout>
    <x-slot:title>Admin Login</x-slot:title>

    <div class="mb-7 flex items-start justify-between gap-6">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal">Admin access</p>
            <h1 class="mt-2 text-2xl font-extrabold leading-tight text-charcoal">
                {{ config('app.name', 'ASNEN') }}
            </h1>
            <p class="mt-1 text-sm text-teal/80">Sign in to manage content and settings.</p>
        </div>
        <div class="shrink-0">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-charcoal/10 bg-white/60 shadow-sm">
                <svg class="h-5 w-5 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 20a8 8 0 0116 0" />
                </svg>
            </div>
        </div>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPw: false }">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-sm font-semibold text-charcoal">{{ __('Email') }}</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-charcoal/40">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full rounded-xl border border-charcoal/20 py-3 pl-10 pr-4 text-sm text-charcoal placeholder:text-charcoal/35 focus:border-brand focus:ring-2 focus:ring-brand/30"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-semibold text-charcoal">{{ __('Password') }}</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-charcoal/40">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m6-7V8a6 6 0 10-12 0v2m12 0H6m12 0a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2" />
                    </svg>
                </span>
                <input
                    id="password"
                    :type="showPw ? 'text' : 'password'"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-xl border border-charcoal/20 py-3 pl-10 pr-12 text-sm text-charcoal placeholder:text-charcoal/35 focus:border-brand focus:ring-2 focus:ring-brand/30"
                >
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-xs font-semibold text-charcoal/50 transition-colors hover:text-brand"
                    @click="showPw = !showPw"
                    :aria-pressed="showPw ? 'true' : 'false'"
                    :aria-label="showPw ? 'Hide password' : 'Show password'"
                >
                    <span x-text="showPw ? 'Hide' : 'Show'">Show</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex select-none items-center gap-2 text-sm text-charcoal/70">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded border-charcoal/25 text-brand focus:ring-brand"
                >
                {{ __('Remember me') }}
            </label>

            <a href="{{ url('/') }}" class="text-sm font-semibold text-brand hover:underline">
                Back to site
            </a>
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-brand py-3 text-sm font-semibold text-white shadow-sm shadow-brand/20 transition-colors hover:bg-brand-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
        >
            {{ __('Log in') }}
        </button>

        <p class="text-xs leading-relaxed text-charcoal/50">
            If you don’t have access, contact an administrator.
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="font-semibold text-brand hover:underline">Forgot your password?</a>
            @endif
        </p>
    </form>
</x-guest-layout>

@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="mb-8">
        <p class="text-sm text-charcoal/60">
            Welcome back, <span class="font-semibold text-charcoal">{{ auth()->user()->name }}</span>.
            Here’s what’s happening across the site.
        </p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('admin.pages.index') }}" class="group relative overflow-hidden rounded-2xl border border-charcoal/10 bg-white p-5 shadow-sm transition hover:border-brand/30 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-charcoal/55">Draft content</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-charcoal">{{ number_format($draftCount) }}</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sand text-charcoal/70 transition group-hover:bg-brand/10 group-hover:text-brand">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-xs font-medium text-brand opacity-0 transition group-hover:opacity-100">Review drafts →</p>
        </a>

        <div class="relative overflow-hidden rounded-2xl border border-charcoal/10 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-charcoal/55">Published</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-brand">{{ number_format($publishedCount) }}</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand/10 text-brand">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-xs text-charcoal/45">Live across pages, stories, events & more</p>
        </div>

        <a href="{{ route('admin.impact-stories.index') }}" class="group relative overflow-hidden rounded-2xl border border-amber-200/80 bg-amber-50/50 p-5 shadow-sm transition hover:border-amber-300 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-amber-800/70">Pending safeguarding</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-amber-800">{{ number_format($pendingSafeguarding) }}</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-xs font-medium text-amber-800/80">
                @if($pendingSafeguarding > 0)
                    Needs review →
                @else
                    All clear
                @endif
            </p>
        </a>

        <a href="{{ route('admin.form-submissions.index') }}" class="group relative overflow-hidden rounded-2xl border border-charcoal/10 bg-white p-5 shadow-sm transition hover:border-brand/30 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-charcoal/55">New submissions</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-charcoal">{{ number_format($newSubmissions) }}</p>
                </div>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sand text-charcoal/70 transition group-hover:bg-brand/10 group-hover:text-brand">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-xs font-medium text-brand opacity-0 transition group-hover:opacity-100">Open inbox →</p>
        </a>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-5">
        <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-sm lg:col-span-3">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-charcoal">Quick actions</h2>
                    <p class="mt-1 text-sm text-charcoal/55">Common tasks to keep content moving.</p>
                </div>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @can('pages.create')
                    <a href="{{ route('admin.pages.create') }}" class="flex items-center gap-3 rounded-xl border border-charcoal/10 bg-sand/40 px-4 py-3 transition hover:border-brand/30 hover:bg-brand/5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-charcoal">New page</span>
                            <span class="block text-xs text-charcoal/50">Create a site page</span>
                        </span>
                    </a>
                @endcan

                @can('media.upload')
                    <a href="{{ route('admin.media.create') }}" class="flex items-center gap-3 rounded-xl border border-charcoal/10 bg-sand/40 px-4 py-3 transition hover:border-brand/30 hover:bg-brand/5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-lime text-charcoal">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-charcoal">Upload media</span>
                            <span class="block text-xs text-charcoal/50">Add images or files</span>
                        </span>
                    </a>
                @endcan

                @can('form_submissions.view')
                    <a href="{{ route('admin.form-submissions.index') }}" class="flex items-center gap-3 rounded-xl border border-charcoal/10 bg-sand/40 px-4 py-3 transition hover:border-brand/30 hover:bg-brand/5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-teal text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-charcoal">Review submissions</span>
                            <span class="block text-xs text-charcoal/50">{{ number_format($newSubmissions) }} waiting</span>
                        </span>
                    </a>
                @endcan

                @can('galleries.view')
                    <a href="{{ route('admin.galleries.index') }}" class="flex items-center gap-3 rounded-xl border border-charcoal/10 bg-sand/40 px-4 py-3 transition hover:border-brand/30 hover:bg-brand/5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-700 text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-charcoal">Manage galleries</span>
                            <span class="block text-xs text-charcoal/50">Photo albums</span>
                        </span>
                    </a>
                @endcan
            </div>
        </section>

        <section class="rounded-2xl border border-charcoal/10 bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="text-base font-bold text-charcoal">Attention</h2>
            <p class="mt-1 text-sm text-charcoal/55">Items that may need a look today.</p>

            <ul class="mt-5 space-y-3">
                <li class="flex items-start gap-3 rounded-xl border border-charcoal/5 bg-sand/30 px-3 py-3">
                    <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 text-xs font-bold">
                        {{ $pendingSafeguarding }}
                    </span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-charcoal">Safeguarding queue</p>
                        <p class="mt-0.5 text-xs text-charcoal/55">Stories, pages, or galleries awaiting approval.</p>
                        @can('impact_stories.view')
                            <a href="{{ route('admin.impact-stories.index') }}" class="mt-1 inline-block text-xs font-semibold text-brand hover:underline">Open success stories</a>
                        @endcan
                    </div>
                </li>
                <li class="flex items-start gap-3 rounded-xl border border-charcoal/5 bg-sand/30 px-3 py-3">
                    <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand/10 text-brand text-xs font-bold">
                        {{ $newSubmissions }}
                    </span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-charcoal">Form inbox</p>
                        <p class="mt-0.5 text-xs text-charcoal/55">New contact and membership messages.</p>
                        @can('form_submissions.view')
                            <a href="{{ route('admin.form-submissions.index') }}" class="mt-1 inline-block text-xs font-semibold text-brand hover:underline">Review now</a>
                        @endcan
                    </div>
                </li>
                <li class="flex items-start gap-3 rounded-xl border border-charcoal/5 bg-sand/30 px-3 py-3">
                    <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-charcoal/10 text-charcoal text-xs font-bold">
                        {{ $draftCount }}
                    </span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-charcoal">Unpublished drafts</p>
                        <p class="mt-0.5 text-xs text-charcoal/55">Content still waiting to go live.</p>
                    </div>
                </li>
            </ul>
        </section>
    </div>
@endsection

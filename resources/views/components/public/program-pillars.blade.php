@props(['programs'])

@if($programs->isEmpty())
    <x-public.empty-state message="Programs will appear here once published." />
@else
    <div class="programs-list border-t border-charcoal/15">
        @foreach($programs as $i => $program)
            @php
                $stroke = ['#0C77BC', '#8CC63F', '#4A4C70', '#FFF200', '#0C77BC', '#8CC63F'][$i % 6];
                $program->loadMissing('featuredImage');
            @endphp
            <a href="{{ route('site.programs.show', $program->slug) }}" class="prog-row group no-underline md:!grid-cols-[7rem_1fr]">
                <div class="w-full max-w-[7rem]">
                    <x-public.media-frame
                        :asset="$program->featuredImage"
                        :alt="$program->featuredImage?->alt ?? $program->title"
                        ratio="1/1"
                        rounded="rounded-lg"
                        label="Photo"
                        class="w-full"
                    />
                </div>
                <div>
                    <div class="mb-1 flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 20 20" aria-hidden="true">
                            <circle cx="10" cy="10" r="7.5" fill="none" stroke="{{ $stroke }}" stroke-width="2"/>
                        </svg>
                        <h3 class="font-display text-xl font-medium text-charcoal group-hover:text-brand">{{ $program->title }}</h3>
                    </div>
                    @if($program->summary)
                        <p class="mt-1 max-w-[60ch] text-charcoal-500">{{ $program->summary }}</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@endif

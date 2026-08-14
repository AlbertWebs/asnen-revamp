@extends('layouts.public')

@section('title', 'Partnership brief: Disability Registration Initiative | '.$siteName)
@section('meta_description', 'Partner with ASNEN, NCPWD and the Ministry of Health: 23 November 2026 webinar and 5 December 2026 Disability Registration Day reaching 500 households.')

@section('content')
    @php $initiative = config('event_pages.initiative', []); @endphp

    <section class="section-editorial">
        <div class="mx-auto max-w-3xl px-6 lg:px-7">
            <div class="partnership-brief">
                <p class="eyebrow mb-3">Africa Special Needs Education Network</p>
                <h1 class="partnership-brief__title">Partner with us</h1>
                <p class="partnership-brief__lede">{{ $initiative['tagline'] ?? 'Inclusion for all, in all. No child left behind.' }}</p>
                <p class="event-partner-block__season">{{ $initiative['season_line'] ?? '' }}</p>

                <div class="partnership-brief__actions no-print">
                    <button type="button" class="btn-primary" onclick="window.print()">Save / print as PDF</button>
                    <a href="{{ route('site.get-involved.partner') }}" class="btn-secondary">Get in touch</a>
                </div>

                <p>{{ $initiative['intro'] ?? '' }}</p>
                <p>{{ $initiative['investment'] ?? '' }}</p>

                <h2>Ways to partner</h2>
                <table class="partnership-brief__table">
                    <thead>
                        <tr>
                            <th>Tier</th>
                            <th>What it includes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($initiative['tiers'] ?? []) as $tier)
                            <tr>
                                <td>{{ $tier['title'] }}</td>
                                <td>{{ $tier['body'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h2>Why partner with ASNEN</h2>
                <ul class="partnership-brief__events">
                    @foreach(($initiative['why'] ?? []) as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>

                <h2>Get in touch</h2>
                <p class="partnership-brief__contact">
                    {{ $initiative['contact']['org'] ?? 'Africa Special Needs Education Network (ASNEN)' }}
                    · {{ $initiative['contact']['city'] ?? 'Nairobi, Kenya' }}
                    · Phone: {{ implode(' | ', $initiative['contact']['phones'] ?? []) }}
                </p>
                <p class="partnership-brief__contact">{{ $initiative['contact']['legal'] ?? '' }}</p>
            </div>
        </div>
    </section>
@endsection

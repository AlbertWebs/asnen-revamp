@extends('layouts.public')

@section('title', 'Search | '.$siteName)

@section('content')
    <div class="mx-auto max-w-editorial px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl font-bold text-forest">Search</h1>
        <form action="{{ route('site.search') }}" method="GET" class="mt-6 max-w-xl">
            <label for="q" class="sr-only">Search query</label>
            <div class="flex gap-2">
                <input type="search" id="q" name="q" value="{{ $query }}" placeholder="Search pages, programs, stories..." class="flex-1 rounded-md border-sand focus:border-gold focus:ring-gold">
                <button type="submit" class="btn-primary">Search</button>
            </div>
        </form>

        @if($query !== '')
            <div class="mt-10">
                @if($results->isEmpty())
                    <x-public.empty-state :message="'No results found for “'.$query.'”.'" />
                @else
                    <ul class="space-y-4">
                        @foreach($results as $result)
                            <li class="rounded-lg border border-sand p-4">
                                <span class="text-xs font-semibold uppercase text-teal">{{ $result['type'] }}</span>
                                <a href="{{ $result['url'] }}" class="mt-1 block font-display text-lg font-semibold text-forest hover:text-teal">{{ $result['title'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
    </div>
@endsection

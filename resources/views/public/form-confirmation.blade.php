@extends('layouts.public')

@section('title', 'Thank You | '.$siteName)

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-20 text-center sm:px-6">
        <div class="rounded-lg border border-sand bg-sand/20 p-10">
            <h1 class="font-display text-3xl font-bold text-forest">Thank you</h1>
            <p class="mt-4 text-lg text-charcoal/80">{{ $message }}</p>
            <a href="{{ route('site.home') }}" class="mt-8 inline-block btn-primary">Return home</a>
        </div>
    </div>
@endsection

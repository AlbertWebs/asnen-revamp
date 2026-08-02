@extends('layouts.public')

@section('title', 'Thank You | '.$siteName)

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-20 text-center">
        <h1 class="font-display text-3xl font-bold text-forest">Thank you for your support</h1>
        <p class="mt-4 text-charcoal/80">Your donation has been processed successfully.</p>
        <a href="{{ route('site.home') }}" class="mt-8 inline-block btn-primary">Return home</a>
    </div>
@endsection

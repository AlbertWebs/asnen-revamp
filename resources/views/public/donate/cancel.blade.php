@extends('layouts.public')

@section('title', 'Donation Cancelled | '.$siteName)

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-20 text-center">
        <h1 class="font-display text-3xl font-bold text-forest">Donation cancelled</h1>
        <p class="mt-4 text-charcoal/80">Your donation was not completed. You can try again or contact us for assistance.</p>
        <a href="{{ route('site.get-involved.donate') }}" class="mt-8 inline-block btn-primary">Return to donate</a>
    </div>
@endsection

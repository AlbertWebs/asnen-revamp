@extends('layouts.public')

@section('title', 'Unsubscribed | '.$siteName)

@section('content')
    <div class="mx-auto max-w-md px-4 py-20 text-center">
        <h1 class="font-display text-3xl font-bold text-forest">Unsubscribed</h1>
        @if($email)
            <p class="mt-4 text-charcoal/80">{{ $email }} has been unsubscribed from ASNEN updates.</p>
        @else
            <p class="mt-4 text-charcoal/80">You have been unsubscribed from ASNEN updates.</p>
        @endif
    </div>
@endsection

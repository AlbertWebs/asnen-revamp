@extends('layouts.public')

@section('title', 'Preview: '.$page->title)

@section('content')
    <div class="bg-gold text-charcoal text-center py-2 text-sm font-semibold">Preview mode - unpublished changes may be visible</div>
    <x-public.blocks :blocks="$page->blocks" :sanitizer="$sanitizer" />
@endsection

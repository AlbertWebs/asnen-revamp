@extends('layouts.public')

@section('title', $page->seoMeta?->title ?? $page->title.' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    @if($easyReadEnabled ?? false)
        <x-public.easy-read :points="[
            'ASNEN works so children and adults with disabilities are included in education and community life.',
            'Our message is: Inclusion for all, in all.',
            'You can change text size, contrast, and motion using the Accessibility button.',
            'You can contact ASNEN, join as a member, volunteer, or partner with us.',
        ]" />
    @endif
    <x-public.blocks :blocks="$page->blocks" :sanitizer="$sanitizer ?? app(\App\Services\HtmlSanitizer::class)" />
@endsection

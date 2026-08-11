@props([
    'kind' => 'file',
    'label' => null,
])

@php
    $kind = in_array($kind, ['pdf', 'word', 'file'], true) ? $kind : 'file';
    $label = $label ?: match ($kind) {
        'pdf' => 'PDF',
        'word' => 'Word',
        default => 'File',
    };
@endphp

<span {{ $attributes->class(['admin-file-icon', 'admin-file-icon--'.$kind]) }} role="img" aria-label="{{ $label }} document">
    <svg class="admin-file-icon__doc" viewBox="0 0 48 56" fill="none" aria-hidden="true">
        <path d="M8 4h22l12 12v36a4 4 0 01-4 4H8a4 4 0 01-4-4V8a4 4 0 014-4z" fill="currentColor" opacity="0.12"/>
        <path d="M8 4h22l12 12v36a4 4 0 01-4 4H8a4 4 0 01-4-4V8a4 4 0 014-4z" stroke="currentColor" stroke-width="2.25" stroke-linejoin="round"/>
        <path d="M30 4v10a2 2 0 002 2h10" stroke="currentColor" stroke-width="2.25" stroke-linejoin="round"/>
        <path d="M14 28h20M14 36h16M14 44h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </svg>
    <span class="admin-file-icon__badge">{{ $label }}</span>
</span>

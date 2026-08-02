@props(['message' => 'Nothing to show yet.', 'action' => null, 'actionLabel' => null])

<div class="rounded-lg border border-dashed border-sand bg-sand/20 px-6 py-12 text-center">
    <p class="text-charcoal/70">{{ $message }}</p>
    @if($action && $actionLabel)
        <a href="{{ $action }}" class="mt-4 inline-block btn-primary">{{ $actionLabel }}</a>
    @endif
</div>

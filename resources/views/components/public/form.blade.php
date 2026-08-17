@props([
    'action',
    'method' => 'POST',
    'submitLabel' => 'Submit application',
    'showSubmit' => true,
])

@if ($errors->any())
    <div class="site-form__errors a11y-form-errors" role="alert" tabindex="-1" id="form-error-summary" data-ajax-summary>
        <p class="font-semibold">Please fix {{ $errors->count() === 1 ? 'this error' : 'these errors' }} and try again:</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form
    action="{{ $action }}"
    method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}"
    {{ $attributes->merge(['class' => 'site-form']) }}
    data-ajax-form
    novalidate
>
    @csrf
    @if (! in_array(strtoupper($method), ['GET', 'POST'], true))
        @method($method)
    @endif

    <div data-ajax-success class="site-form__success" hidden role="status"></div>
    <div data-ajax-done class="site-form__done" hidden role="status"></div>

    <div data-ajax-body>
        {{ $slot }}

        <input type="text" name="website" tabindex="-1" autocomplete="off" class="site-form__honeypot" aria-hidden="true">
        <input type="hidden" name="math_token" value="">
        <input type="hidden" name="math_answer" value="">

        @if($showSubmit)
            <button type="submit" class="btn-primary site-form__submit">{{ $submitLabel }}</button>
        @endif
    </div>
</form>

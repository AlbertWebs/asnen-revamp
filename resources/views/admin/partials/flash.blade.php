@if (session('success'))
    <div class="mx-4 mt-4 rounded-xl border border-lime/30 bg-lime/10 px-4 py-3 text-sm text-charcoal lg:mx-8" role="status">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mx-4 mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 lg:mx-8" role="alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mx-4 mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 lg:mx-8" role="alert">
        <ul class="list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

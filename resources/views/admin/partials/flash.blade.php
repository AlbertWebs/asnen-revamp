@if (session('success'))
    <div class="mx-4 mt-4 rounded-md border border-forest-200 bg-forest-50 px-4 py-3 text-sm text-forest-900 lg:mx-6" role="status">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mx-4 mt-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 lg:mx-6" role="alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mx-4 mt-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 lg:mx-6" role="alert">
        <ul class="list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

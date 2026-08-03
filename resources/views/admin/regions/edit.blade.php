@extends('layouts.admin')

@section('title', $region->exists ? 'Edit Region' : 'New Region')
@section('heading', $region->exists ? 'Edit Region' : 'New Region')

@section('content')
    @if ($region->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $region, 'routePrefix' => 'regions'])
        </div>
    @endif

    <form method="POST" action="{{ $region->exists ? route('admin.regions.update', $region) : route('admin.regions.store') }}">
        @csrf
        @if ($region->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,24rem)]">
            <div class="space-y-4 rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
                <div>
                    <label for="name" class="block text-sm font-medium text-charcoal-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $region->name) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-charcoal-700">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $region->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="country" class="block text-sm font-medium text-charcoal-700">Country</label>
                    <input type="text" name="country" id="country" value="{{ old('country', $region->country) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="impact_label" class="block text-sm font-medium text-charcoal-700">Impact label</label>
                    <input type="text" name="impact_label" id="impact_label" value="{{ old('impact_label', $region->impact_label) }}" placeholder="e.g. Medical camp · 2023" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-charcoal-700">Description</label>
                    <textarea name="description" id="description" rows="5" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('description', $region->description) }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="link_url" class="block text-sm font-medium text-charcoal-700">Link URL</label>
                        <input type="text" name="link_url" id="link_url" value="{{ old('link_url', $region->link_url) }}" placeholder="/impact/komolion" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                    </div>
                    <div>
                        <label for="link_label" class="block text-sm font-medium text-charcoal-700">Link label</label>
                        <input type="text" name="link_label" id="link_label" value="{{ old('link_label', $region->link_label) }}" placeholder="Read case study" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="map_color" class="block text-sm font-medium text-charcoal-700">Map colour</label>
                        <input type="color" name="map_color" id="map_color" value="{{ old('map_color', $region->map_color ?: ($region->is_featured ? '#8CC63F' : '#0C77BC')) }}" class="mt-1 h-10 w-full cursor-pointer rounded-md border border-charcoal-300 bg-white p-1 shadow-sm">
                        <p class="mt-1 text-xs text-charcoal-500">Used to colour this region’s reach area on the public map.</p>
                    </div>
                    <div>
                        <label for="reach_radius_km" class="block text-sm font-medium text-charcoal-700">Reach radius (km)</label>
                        <input type="number" min="1" max="500" name="reach_radius_km" id="reach_radius_km" value="{{ old('reach_radius_km', $region->reach_radius_km) }}" placeholder="e.g. 12" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                        <p class="mt-1 text-xs text-charcoal-500">Draws a coloured circle when no county boundary is set.</p>
                    </div>
                </div>
                <div>
                    <label for="boundary_geojson" class="block text-sm font-medium text-charcoal-700">Boundary GeoJSON (optional)</label>
                    <textarea name="boundary_geojson" id="boundary_geojson" rows="6" placeholder='{"type":"Polygon","coordinates":[[[lng,lat],...]]}' class="mt-1 block w-full rounded-md border-charcoal-300 font-mono text-xs shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('boundary_geojson', $region->boundary_geojson ? json_encode($region->boundary_geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
                    <p class="mt-1 text-xs text-charcoal-500">Paste a Polygon or MultiPolygon geometry to shade the county or area of reach.</p>
                    @error('boundary_geojson')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-charcoal-700">Sort order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $region->sort_order ?? 0) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="inline-flex items-center gap-2 text-sm text-charcoal-700">
                            <input type="checkbox" name="is_featured" value="1" class="rounded border-charcoal-300 text-forest-700 focus:ring-forest-500" @checked(old('is_featured', $region->is_featured))>
                            Featured on map
                        </label>
                    </div>
                </div>
            </div>

            <div
                class="rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm"
                x-data="regionMapPicker({
                    lat: @js(old('latitude', $region->latitude) !== null && old('latitude', $region->latitude) !== '' ? (float) old('latitude', $region->latitude) : null),
                    lng: @js(old('longitude', $region->longitude) !== null && old('longitude', $region->longitude) !== '' ? (float) old('longitude', $region->longitude) : null),
                })"
            >
                <h2 class="text-sm font-semibold text-charcoal-800">Map pin</h2>
                <p class="mt-1 text-xs text-charcoal-500">Click the map to place the pin, or type coordinates below.</p>

                <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                    <div>
                        <label for="latitude" class="block text-xs font-medium uppercase tracking-wide text-charcoal-500">Latitude</label>
                        <input type="number" step="any" name="latitude" id="latitude" x-model="lat" @change="syncFromInputs()" class="mt-1 block w-full rounded-md border-charcoal-300 text-sm shadow-sm focus:border-forest-500 focus:ring-forest-500">
                    </div>
                    <div>
                        <label for="longitude" class="block text-xs font-medium uppercase tracking-wide text-charcoal-500">Longitude</label>
                        <input type="number" step="any" name="longitude" id="longitude" x-model="lng" @change="syncFromInputs()" class="mt-1 block w-full rounded-md border-charcoal-300 text-sm shadow-sm focus:border-forest-500 focus:ring-forest-500">
                    </div>
                </div>

                <div class="admin-region-map mt-3 overflow-hidden rounded-md border border-charcoal-200" x-ref="map" style="height: 280px;"></div>
                <p class="mt-2 text-xs text-charcoal-500">Default view centres on Kenya. Drag the pin or click to update.</p>
            </div>
        </div>
    </form>
@endsection

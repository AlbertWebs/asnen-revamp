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

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,24rem)]">
            <div class="admin-form admin-form--full">
                <div class="admin-form__body">
                    <header class="admin-form__header">
                        <p class="admin-form__eyebrow">Regions</p>
                        <h2 class="admin-form__title">{{ $region->exists ? 'Edit region' : 'New region' }}</h2>
                    </header>

                    <div class="admin-form__section">
                        <div class="admin-field">
                            <label for="name" class="admin-label">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $region->name) }}" required class="admin-input">
                        </div>
                        <div class="admin-field">
                            <label for="slug" class="admin-label">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $region->slug) }}" class="admin-input">
                        </div>
                        <div class="admin-field">
                            <label for="country" class="admin-label">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country', $region->country) }}" class="admin-input">
                        </div>
                        <div class="admin-field">
                            <label for="impact_label" class="admin-label">Impact label</label>
                            <input type="text" name="impact_label" id="impact_label" value="{{ old('impact_label', $region->impact_label) }}" placeholder="e.g. Medical camp · 2023" class="admin-input">
                        </div>
                        <div class="admin-field">
                            <label for="description" class="admin-label">Description</label>
                            <textarea name="description" id="description" rows="5" class="admin-textarea">{{ old('description', $region->description) }}</textarea>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="admin-field">
                                <label for="link_url" class="admin-label">Link URL</label>
                                <input type="text" name="link_url" id="link_url" value="{{ old('link_url', $region->link_url) }}" placeholder="/impact/stories/komolion-2023-disability-assessment-medical-camp" class="admin-input">
                            </div>
                            <div class="admin-field">
                                <label for="link_label" class="admin-label">Link label</label>
                                <input type="text" name="link_label" id="link_label" value="{{ old('link_label', $region->link_label) }}" placeholder="Read case study" class="admin-input">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="admin-field">
                                <label for="map_color" class="admin-label">Map colour</label>
                                <input type="color" name="map_color" id="map_color" value="{{ old('map_color', $region->map_color ?: ($region->is_featured ? '#8CC63F' : '#0C77BC')) }}" class="h-10 w-full cursor-pointer rounded-xl border border-charcoal/15 bg-white p-1 shadow-sm">
                                <p class="admin-hint">Used to colour this region’s reach area on the public map.</p>
                            </div>
                            <div class="admin-field">
                                <label for="reach_radius_km" class="admin-label">Reach radius (km)</label>
                                <input type="number" min="1" max="500" name="reach_radius_km" id="reach_radius_km" value="{{ old('reach_radius_km', $region->reach_radius_km) }}" placeholder="e.g. 12" class="admin-input">
                                <p class="admin-hint">Draws a coloured circle when no county boundary is set.</p>
                            </div>
                        </div>
                        <div class="admin-field">
                            <label for="boundary_geojson" class="admin-label">Boundary GeoJSON (optional)</label>
                            <textarea name="boundary_geojson" id="boundary_geojson" rows="6" placeholder='{"type":"Polygon","coordinates":[[[lng,lat],...]]}' class="admin-textarea admin-textarea--plain font-mono text-xs" data-plain data-rich-editor="false">{{ old('boundary_geojson', $region->boundary_geojson ? json_encode($region->boundary_geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
                            <p class="admin-hint">Paste a Polygon or MultiPolygon geometry to shade the county or area of reach.</p>
                            @error('boundary_geojson')
                                <p class="admin-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="admin-field">
                                <label for="sort_order" class="admin-label">Sort order</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $region->sort_order ?? 0) }}" class="admin-input">
                            </div>
                            <div class="flex items-end pb-2">
                                <label class="admin-check">
                                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $region->is_featured))>
                                    <span>Featured on map</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="admin-form__actions">
                        <button type="submit" class="admin-btn-primary">Save</button>
                    </div>
                </div>
            </div>

            <div
                class="admin-form admin-form--full"
                x-data="regionMapPicker({
                    lat: @js(old('latitude', $region->latitude) !== null && old('latitude', $region->latitude) !== '' ? (float) old('latitude', $region->latitude) : null),
                    lng: @js(old('longitude', $region->longitude) !== null && old('longitude', $region->longitude) !== '' ? (float) old('longitude', $region->longitude) : null),
                })"
            >
                <div class="admin-form__body">
                    <p class="admin-form__section-title">Map pin</p>
                    <p class="admin-hint">Click the map to place the pin, or type coordinates below.</p>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                        <div class="admin-field">
                            <label for="latitude" class="admin-label">Latitude</label>
                            <input type="number" step="any" name="latitude" id="latitude" x-model="lat" @change="syncFromInputs()" class="admin-input">
                        </div>
                        <div class="admin-field">
                            <label for="longitude" class="admin-label">Longitude</label>
                            <input type="number" step="any" name="longitude" id="longitude" x-model="lng" @change="syncFromInputs()" class="admin-input">
                        </div>
                    </div>

                    <div class="admin-region-map overflow-hidden rounded-xl border border-charcoal/10" x-ref="map" style="height: 280px;"></div>
                    <p class="admin-hint">Default view centres on Kenya. Drag the pin or click to update.</p>
                </div>
            </div>
        </div>
    </form>
@endsection

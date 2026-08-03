<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'latitude',
        'longitude',
        'boundary_geojson',
        'reach_radius_km',
        'map_color',
        'country',
        'impact_label',
        'link_url',
        'link_label',
        'is_featured',
        'sort_order',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'boundary_geojson' => 'array',
            'reach_radius_km' => 'integer',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected function getSlugSource(): string
    {
        return $this->name;
    }

    protected function getSlugSourceAttributes(): array
    {
        return ['name'];
    }

    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public function hasBoundary(): bool
    {
        return is_array($this->boundary_geojson)
            && ($this->boundary_geojson['type'] ?? null)
            && ! empty($this->boundary_geojson['coordinates']);
    }

    public function resolvedMapColor(): string
    {
        if (filled($this->map_color)) {
            return $this->map_color;
        }

        return $this->is_featured ? '#8CC63F' : '#0C77BC';
    }

    public function toMapPayload(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'boundary' => $this->hasBoundary() ? $this->boundary_geojson : null,
            'reach_radius_km' => $this->reach_radius_km,
            'map_color' => $this->resolvedMapColor(),
            'country' => $this->country,
            'impact_label' => $this->impact_label,
            'link_url' => $this->link_url,
            'link_label' => $this->link_label ?: 'Learn more',
            'is_featured' => (bool) $this->is_featured,
        ];
    }
}

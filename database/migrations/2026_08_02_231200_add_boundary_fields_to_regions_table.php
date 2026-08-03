<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->json('boundary_geojson')->nullable()->after('longitude');
            $table->unsignedSmallInteger('reach_radius_km')->nullable()->after('boundary_geojson');
            $table->string('map_color', 20)->nullable()->after('reach_radius_km');
        });
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn(['boundary_geojson', 'reach_radius_km', 'map_color']);
        });
    }
};

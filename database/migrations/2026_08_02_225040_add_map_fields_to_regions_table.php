<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('description');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('country')->nullable()->after('longitude');
            $table->string('impact_label')->nullable()->after('country');
            $table->string('link_url')->nullable()->after('impact_label');
            $table->string('link_label')->nullable()->after('link_url');
            $table->boolean('is_featured')->default(false)->after('link_label');
        });
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'country',
                'impact_label',
                'link_url',
                'link_label',
                'is_featured',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('allow_registration')->default(true);
        });

        $profiles = config('event_pages', []);
        if (is_array($profiles)) {
            foreach ($profiles as $slug => $profile) {
                if (! is_array($profile) || ! array_key_exists('allow_registration', $profile)) {
                    continue;
                }
                \Illuminate\Support\Facades\DB::table('events')
                    ->where('slug', $slug)
                    ->update(['allow_registration' => (bool) $profile['allow_registration']]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('allow_registration');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('group')->index();
            $table->json('value');
            $table->boolean('is_public')->default(false);
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path')->unique();
            $table->string('to_path');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('hits')->default(0);
        });

        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('disk');
            $table->string('path');
            $table->string('filename');
            $table->string('mime');
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt')->nullable();
            $table->text('caption')->nullable();
            $table->string('credit')->nullable();
            $table->string('copyright')->nullable();
            $table->string('folder')->nullable()->index();
            $table->json('tags')->nullable();
            $table->decimal('focal_point_x', 8, 4)->nullable();
            $table->decimal('focal_point_y', 8, 4)->nullable();
            $table->boolean('is_private')->default(false);
            $table->string('consent_status')->default('pending');
            $table->text('consent_notes')->nullable();
            $table->timestamp('consented_at')->nullable();
            $table->foreignId('consented_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('variants')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('media_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_asset_id')->constrained('media_assets')->cascadeOnDelete();
            $table->morphs('usable');
            $table->string('field')->nullable();
            $table->timestamps();

            $table->index(['media_asset_id', 'usable_type', 'usable_id']);
        });

        Schema::create('consent_records', function (Blueprint $table) {
            $table->id();
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');
            $table->string('consent_type');
            $table->string('status')->default('pending');
            $table->string('granted_by_name');
            $table->string('granted_by_relation')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
        });

        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots')->nullable();
            $table->foreignId('og_image_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->json('schema_json')->nullable();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('template')->default('default');
            $table->text('excerpt')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('unpublished_at')->nullable();
            $table->string('timezone')->default('Africa/Nairobi');
            $table->boolean('requires_safeguarding')->default(false);
            $table->string('safeguarding_status')->default('not_required');
            $table->string('verification_status')->default('verified');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('editor_notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'published_at']);
        });

        Schema::create('page_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('type');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->json('content');
            $table->json('settings')->nullable();
            $table->timestamp('scheduled_from')->nullable();
            $table->timestamp('scheduled_until')->nullable();
            $table->string('anchor_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['page_id', 'sort_order']);
        });

        Schema::create('content_revisions', function (Blueprint $table) {
            $table->id();
            $table->morphs('revisionable');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('snapshot');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->unique();
            $table->timestamps();
        });

        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('navigation_menus')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_items')->nullOnDelete();
            $table->string('label');
            $table->string('url')->nullable();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('route_name')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['menu_id', 'sort_order']);
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('body')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('featured_image_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('verification_status')->default('needs_verification');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->foreignId('logo_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('category')->nullable();
            $table->date('partnership_start')->nullable();
            $table->date('partnership_end')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('verification_status')->default('needs_verification');
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('impact_stories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('body');
            $table->string('location')->nullable();
            $table->date('story_date')->nullable();
            $table->foreignId('featured_image_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->boolean('requires_safeguarding')->default(true);
            $table->string('safeguarding_status')->default('pending');
            $table->string('verification_status')->default('needs_verification');
            $table->text('challenges')->nullable();
            $table->text('learnings')->nullable();
            $table->text('next_steps')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('impact_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('value');
            $table->decimal('numeric_value', 20, 4)->nullable();
            $table->string('unit')->nullable();
            $table->string('qualifier')->nullable();
            $table->string('source_label')->nullable();
            $table->date('as_of_date')->nullable();
            $table->string('region')->nullable();
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->string('verification_status')->default('needs_verification');
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('public_label')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('story_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('impact_story_id')->constrained('impact_stories')->cascadeOnDelete();
            $table->string('label');
            $table->string('value')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['impact_story_id', 'sort_order']);
        });

        Schema::create('impact_story_partner', function (Blueprint $table) {
            $table->foreignId('impact_story_id')->constrained('impact_stories')->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();

            $table->primary(['impact_story_id', 'partner_id']);
        });

        Schema::create('impact_story_program', function (Blueprint $table) {
            $table->foreignId('impact_story_id')->constrained('impact_stories')->cascadeOnDelete();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();

            $table->primary(['impact_story_id', 'program_id']);
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title_role');
            $table->text('bio')->nullable();
            $table->foreignId('photo_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_board')->default(false);
            $table->string('verification_status')->default('needs_verification');
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('event');
            $table->text('summary')->nullable();
            $table->longText('body')->nullable();
            $table->string('venue')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('online_url')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->string('timezone')->default('Africa/Nairobi');
            $table->unsignedInteger('capacity')->nullable();
            $table->timestamp('registration_opens_at')->nullable();
            $table->timestamp('registration_closes_at')->nullable();
            $table->foreignId('featured_image_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('verification_status')->default('needs_verification');
            $table->softDeletes();
            $table->timestamps();

            $table->index('starts_at');
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organization')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('consent_marketing')->default(false);
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['event_id', 'status']);
        });

        Schema::create('webinars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('body')->nullable();
            $table->dateTime('held_at')->nullable();
            $table->string('moderator')->nullable();
            $table->unsignedInteger('participant_count')->nullable();
            $table->string('recording_url')->nullable();
            $table->longText('transcript')->nullable();
            $table->foreignId('featured_image_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('verification_status')->default('needs_verification');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('webinar_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webinar_id')->constrained('webinars')->cascadeOnDelete();
            $table->string('title');
            $table->string('url')->nullable();
            $table->foreignId('media_asset_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('type');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['webinar_id', 'sort_order']);
        });

        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('other');
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('abstract')->nullable();
            $table->json('authors')->nullable();
            $table->string('version')->nullable();
            $table->foreignId('cover_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->foreignId('file_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->foreignId('accessible_file_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->unsignedInteger('download_count')->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('verification_status')->default('needs_verification');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->string('category')->nullable();
            $table->unsignedSmallInteger('reading_time_minutes')->nullable();
            $table->foreignId('featured_image_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->morphs('taggable');

            $table->primary(['tag_id', 'taggable_id', 'taggable_type']);
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->text('quote');
            $table->string('attribution_name')->nullable();
            $table->string('attribution_role')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('consent_status')->default('pending');
            $table->timestamp('consent_expires_at')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->date('gallery_date')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->boolean('requires_safeguarding')->default(false);
            $table->string('safeguarding_status')->default('not_required');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('galleries')->cascadeOnDelete();
            $table->foreignId('media_asset_id')->constrained('media_assets')->cascadeOnDelete();
            $table->text('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['gallery_id', 'sort_order']);
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['category', 'sort_order']);
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->string('link_url')->nullable();
            $table->string('link_label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->json('benefits')->nullable();
            $table->text('eligibility')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('form_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type');
            $table->json('fields');
            $table->text('success_message')->nullable();
            $table->json('notify_emails')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('retention_days')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_definition_id')->constrained('form_definitions')->cascadeOnDelete();
            $table->json('data');
            $table->string('status')->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->boolean('honeypot_caught')->default(false);
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('consent_at')->nullable();
            $table->string('confirmation_token')->nullable()->unique();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['form_definition_id', 'status']);
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('status')->default('active');
            $table->string('source')->nullable();
            $table->timestamp('consent_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('donation_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('body')->nullable();
            $table->decimal('goal_amount', 15, 2)->nullable();
            $table->string('currency', 3)->default('KES');
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
        Schema::dropIfExists('donation_campaigns');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_definitions');
        Schema::dropIfExists('membership_plans');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('publications');
        Schema::dropIfExists('webinar_resources');
        Schema::dropIfExists('webinars');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('impact_story_program');
        Schema::dropIfExists('impact_story_partner');
        Schema::dropIfExists('story_outcomes');
        Schema::dropIfExists('impact_metrics');
        Schema::dropIfExists('impact_stories');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('navigation_menus');
        Schema::dropIfExists('content_revisions');
        Schema::dropIfExists('page_blocks');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('seo_meta');
        Schema::dropIfExists('consent_records');
        Schema::dropIfExists('media_usages');
        Schema::dropIfExists('media_assets');
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('settings');
    }
};

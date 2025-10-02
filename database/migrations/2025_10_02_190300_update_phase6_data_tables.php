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
        Schema::table('programs', function (Blueprint $table) {
            $table->decimal('monthly_rate', 8, 2)->nullable()->after('display_order');
            $table->decimal('transport_fee', 6, 2)->nullable()->after('monthly_rate');
            $table->unsignedSmallInteger('capacity_daily')->nullable()->after('transport_fee');
            $table->string('availability_status')->default('available')->after('capacity_daily');
            $table->json('languages_supported')->nullable()->after('availability_status');
            $table->string('analytics_tag')->nullable()->after('languages_supported');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->string('credentials')->nullable()->after('role');
            $table->json('languages_spoken')->nullable()->after('credentials');
            $table->unsignedTinyInteger('years_experience')->nullable()->after('languages_spoken');
            $table->boolean('on_call')->default(false)->after('years_experience');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('program_slug')->nullable()->after('location');
            $table->string('language', 5)->default('en')->after('program_slug');
            $table->timestamp('submitted_at')->nullable()->after('language');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('category');
            $table->string('audience')->nullable()->after('tags');
            $table->boolean('featured')->default(false)->after('audience');
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->string('resource_type')->default('pdf')->after('description');
            $table->boolean('requires_login')->default(false)->after('resource_type');
            $table->string('external_url')->nullable()->after('requires_login');
            $table->string('preview_image')->nullable()->after('external_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn([
                'monthly_rate',
                'transport_fee',
                'capacity_daily',
                'availability_status',
                'languages_supported',
                'analytics_tag',
            ]);
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn([
                'credentials',
                'languages_spoken',
                'years_experience',
                'on_call',
            ]);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn([
                'program_slug',
                'language',
                'submitted_at',
            ]);
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn([
                'tags',
                'audience',
                'featured',
            ]);
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn([
                'resource_type',
                'requires_login',
                'external_url',
                'preview_image',
            ]);
        });
    }
};

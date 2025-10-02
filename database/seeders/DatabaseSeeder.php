<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Phase 6 (Data & Integration Hardening) ordering ensures program slugs and
     * resource metadata exist before dependent records (e.g. testimonials).
     * Run via: php artisan db:seed or php artisan migrate:fresh --seed.
     */
    public function run(): void
    {
        $this->call([
            ProgramSeeder::class,
            StaffSeeder::class,
            TestimonialSeeder::class,
            ResourceSeeder::class,
            FaqSeeder::class,
        ]);
    }
}

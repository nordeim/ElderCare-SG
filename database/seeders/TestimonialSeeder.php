<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'author_name' => 'Adeline Tan',
                'author_relation' => 'Daughter of resident',
                'location' => 'Bukit Timah',
                'program_slug' => 'day-programs',
                'language' => 'en',
                'submitted_at' => now()->copy()->subDays(12)->setTime(9, 15),
                'rating' => 5,
                'quote' => 'Mum looks forward to every visit. The staff communicates with compassion and clarity, always keeping us informed.',
                'is_featured' => true,
                'display_order' => 1,
            ],
            [
                'author_name' => 'Harish Kumar',
                'author_relation' => 'Son of resident',
                'location' => 'Jurong West',
                'program_slug' => 'weekend-respite',
                'language' => 'en',
                'submitted_at' => now()->copy()->subDays(15)->setTime(11, 40),
                'rating' => 5,
                'quote' => 'Weekend respite gave our family breathing room. Dad loves the outings and we get quality rest.',
                'display_order' => 2,
            ],
            [
                'author_name' => 'Nur Aisyah',
                'author_relation' => 'Primary caregiver',
                'location' => 'Punggol',
                'program_slug' => 'memory-care-mornings',
                'language' => 'ms',
                'submitted_at' => now()->copy()->subDays(18)->setTime(14, 5),
                'rating' => 5,
                'quote' => 'I trust their dementia team completely — the daily updates keep me assured even on my busiest days.',
                'display_order' => 3,
            ],
            [
                'author_name' => 'Lee Mei Hua',
                'author_relation' => 'Resident',
                'location' => 'Ang Mo Kio',
                'program_slug' => 'caregiver-masterclasses',
                'language' => 'zh',
                'submitted_at' => now()->copy()->subDays(8)->setTime(16, 20),
                'rating' => 5,
                'quote' => '老师们会用中文和我练习，自信慢慢回来。我也学会了如何提醒女儿休息。',
                'display_order' => 4,
            ],
            [
                'author_name' => 'Dr. William Chan',
                'author_relation' => 'Partner Geriatrician',
                'location' => 'Mount Elizabeth Novena',
                'program_slug' => 'night-owl-support',
                'language' => 'en',
                'submitted_at' => now()->copy()->subDays(21)->setTime(10, 30),
                'rating' => 5,
                'quote' => 'The overnight respite program provides clinically sound handovers and gives caregivers peace of mind for critical rest.',
                'is_featured' => true,
                'display_order' => 5,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate([
                'author_name' => $testimonial['author_name'],
                'author_relation' => $testimonial['author_relation'],
            ], $testimonial);
        }
    }
}

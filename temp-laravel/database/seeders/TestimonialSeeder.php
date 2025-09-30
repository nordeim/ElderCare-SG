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
                'rating' => 5,
                'quote' => 'Mum looks forward to every visit. The staff communicates with compassion and clarity, always keeping us informed.',
                'is_featured' => true,
                'display_order' => 1,
            ],
            [
                'author_name' => 'Harish Kumar',
                'author_relation' => 'Son of resident',
                'location' => 'Jurong West',
                'rating' => 5,
                'quote' => 'Their wellness programs rebuilt Dad’s confidence after his fall. He is stronger and more social than before.',
                'display_order' => 2,
            ],
            [
                'author_name' => 'Nur Aisyah',
                'author_relation' => 'Primary caregiver',
                'location' => 'Punggol',
                'rating' => 5,
                'quote' => 'I trust their team completely — the daily updates keep me assured even on my busiest days.',
                'display_order' => 3,
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

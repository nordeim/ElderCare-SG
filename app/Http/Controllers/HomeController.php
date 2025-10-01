<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Program;
use App\Models\Resource;
use App\Models\Staff;
use App\Models\Testimonial;
use App\Services\BookingService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function __invoke(BookingService $bookingService)
    {
        $programs = Program::active()->orderBy('display_order')->get();
        $testimonials = Testimonial::active()->orderBy('display_order')->get();
        $featuredStaff = Staff::query()->where('is_featured', true)->orderBy('name')->get();
        $faqs = Faq::active()->orderBy('display_order')->get();
        $resources = Resource::query()->where('is_active', true)->orderBy('display_order')->get();

        $tourHotspots = $this->loadTourHotspots();
        $tourTranscriptUrl = File::exists(public_path('media/transcripts/tour-main.md'))
            ? '/media/transcripts/tour-main.md'
            : null;

        return view('home', [
            'programs' => $programs,
            'testimonials' => $testimonials,
            'bookingUrl' => $bookingService->bookingUrl(),
            'tourHotspots' => $tourHotspots,
            'tourTranscriptUrl' => $tourTranscriptUrl,
            'featuredStaff' => $featuredStaff,
            'faqs' => $faqs,
            'resources' => $resources,
        ]);
    }

    private function loadTourHotspots(): Collection
    {
        $path = resource_path('data/tour_hotspots.json');

        if (! File::exists($path)) {
            return collect();
        }

        $decoded = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

        return collect($decoded)->map(function ($hotspot) {
            $hotspot['anchor'] = isset($hotspot['id']) ? '#' . $hotspot['id'] : null;

            return $hotspot;
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Testimonial;
use App\Services\BookingService;

class HomeController extends Controller
{
    public function __invoke(BookingService $bookingService)
    {
        $programs = Program::active()->orderBy('display_order')->get();
        $testimonials = Testimonial::active()->orderBy('display_order')->get();

        return view('home', [
            'programs' => $programs,
            'testimonials' => $testimonials,
            'bookingUrl' => $bookingService->bookingUrl(),
        ]);
    }
}

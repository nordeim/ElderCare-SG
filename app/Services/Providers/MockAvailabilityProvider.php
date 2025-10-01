<?php

namespace App\Services\Providers;

use Carbon\CarbonImmutable;

class MockAvailabilityProvider implements AvailabilityProvider
{
    public function __construct(
        protected int $windowDays = 7,
        protected int $weeklySlots = 18,
        protected string $timezone = 'Asia/Singapore'
    ) {
        $this->windowDays = max(1, $this->windowDays);
        $this->weeklySlots = max($this->windowDays, $this->weeklySlots);
    }

    public function fetch(): array
    {
        $now = CarbonImmutable::now($this->timezone);
        $averagePerDay = (int) floor($this->weeklySlots / $this->windowDays);
        $averagePerDay = max(1, $averagePerDay);
        $remainder = $this->weeklySlots % $this->windowDays;

        $slots = [];

        for ($day = 0; $day < $this->windowDays; $day++) {
            $available = $averagePerDay + ($day < $remainder ? 1 : 0);

            $slots[] = [
                'date' => $now->addDays($day)->startOfDay()->toDateString(),
                'available' => $available,
            ];
        }

        return [
            'status' => 'ok',
            'slots' => $slots,
            'updated_at' => $now->toIso8601String(),
        ];
    }
}

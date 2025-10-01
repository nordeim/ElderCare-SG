<?php

namespace App\Services\Providers;

use Illuminate\Support\Collection;

interface AvailabilityProvider
{
    /**
     * Fetch latest availability information.
     *
     * @return array{
     *     status?: string,
     *     slots?: array<int, array{date: string, available: int}>,
     *     updated_at?: string|null
     * }
     */
    public function fetch(): array;
}

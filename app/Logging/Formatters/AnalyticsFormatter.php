<?php

namespace App\Logging\Formatters;

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;

class AnalyticsFormatter
{
    /**
     * Customize the given logger instance.
     */
    public function __invoke(Logger $logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_NEWLINES, true));
        }
    }
}

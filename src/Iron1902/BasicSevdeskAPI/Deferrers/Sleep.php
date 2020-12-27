<?php

namespace Iron1902\BasicSevdeskAPI\Deferrers;

use Iron1902\BasicSevdeskAPI\Contracts\TimeDeferrer;

/**
 * Base time deferrer implementation.
 * Based on spatie/guzzle-rate-limiter-middleware.
 */
class Sleep implements TimeDeferrer
{
    /**
     * {@inheritdoc}
     */
    public function getCurrentTime(): float
    {
        return microtime(true);
    }

    /**
     * {@inheritdoc}
     */
    public function sleep(float $microseconds): void
    {
        usleep($microseconds);
    }
}

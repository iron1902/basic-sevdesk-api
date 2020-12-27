<?php

namespace Iron1902\BasicSevdeskAPI\Contracts;

/**
 * Reprecents time tracking.
 */
interface TimeAccesser
{
    /**
     * Get the time store implementation.
     *
     * @return StateStorage
     */
    public function getTimeStore(): StateStorage;

    /**
     * Get the time deferrer implementation.
     *
     * @return TimeDeferrer
     */
    public function getTimeDeferrer(): TimeDeferrer;
}

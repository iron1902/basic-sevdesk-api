<?php

namespace Iron1902\BasicSevdeskAPI\Contracts;

/**
 * Reprecents basic state storage.
 * Based on spatie/guzzle-rate-limiter-middleware.
 */
interface StateStorage
{
    /**
     * Get all container values.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Get the values.
     *
     *
     * @return array
     */
    public function get(): array;

    /**
     * Set the values.
     *
     * @param array   $values  The values to set.
     *
     * @return void
     */
    public function set(array $values): void;

    /**
     * Set the values.
     *
     * @param mixed   $value   The value to add.
     *
     * @return void
     */
    public function push($value): void;

    /**
     * Remove all values.
     *
     *
     * @return void
     */
    public function reset(): void;
}

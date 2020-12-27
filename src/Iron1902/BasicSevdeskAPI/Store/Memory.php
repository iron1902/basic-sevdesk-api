<?php

namespace Iron1902\BasicSevdeskAPI\Store;

use Iron1902\BasicSevdeskAPI\Contracts\StateStorage;

/**
 * In-memory storage for timestamps used by rate limit middleware.
 * Based on spatie/guzzle-rate-limiter-middleware.
 */
class Memory implements StateStorage
{
    /**
     * The data container.
     *
     * @var array
     */
    protected $container = [];

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function set(array $values): void
    {
        $this->container = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function push($value): void
    {
        if (!isset($this->container)) {
            $this->reset();
        }

        array_unshift($this->container, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function reset(): void
    {
        $this->container = [];
    }
}
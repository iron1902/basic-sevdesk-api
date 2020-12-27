<?php

namespace Iron1902\BasicSevdeskAPI\Contracts;

use GuzzleHttp\ClientInterface;
use Iron1902\BasicSevdeskAPI\Options;

/**
 * Reprecents Guzzle client awareness.
 */
interface ClientAware
{
    /**
     * Set the Guzzle client.
     *
     * @param ClientInterface $client
     *
     * @return void
     */
    public function setClient(ClientInterface $client): void;

    /**
     * Get the client.
     *
     * @return ClientInterface
     */
    public function getClient(): ClientInterface;

    /**
     * Set the options.
     *
     * @param Options $options
     *
     * @return void
     */
    public function setOptions(Options $options): void;

    /**
     * Get the options.
     *
     * @return Options
     */
    public function getOptions(): Options;
}

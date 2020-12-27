<?php

namespace Iron1902\BasicSevdeskAPI;

use Exception;

/**
 * Options for the library.
 */
class Options
{

    /**
     * The Sevdesk API key.
     *
     * @var string|null
     */
    protected $apiKey;

    /**
     * The Sevdesk API Endpoint.
     *
     * @var string|null
     */
    protected $apiEndpoint = 'https://my.sevdesk.de/api/v1/';

    /**
     * How many requests allowed per second.
     *
     * @var int
     */
    protected $restLimit = 100;


    /**
     * Additional Guzzle options.
     *
     * @var array
     */
    protected $guzzleOptions = [
        'headers'                  => [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ],
        'timeout'                  => 10.0,
        'max_retry_attempts'       => 2,
        'default_retry_multiplier' => 2.0,
        'retry_on_status'          => [429, 503, 500],
    ];

    /**
     * Optional Guzzle handler to use.
     *
     * @var callable|null
     */
    protected $guzzleHandler;

    /**
     * Sets the API key for use with the Sevdesk API 
     *
     * @param string $apiKey The API key.
     *
     * @return self
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get the API key.
     *
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }


    /**
     * Sets the API endpoint for use with the Sevdesk API 
     *
     * @param string $apiEndpoint The API key.
     *
     * @return self
     */
    public function setApiEndpoint(string $apiEndpoint): self
    {
        $this->apiEndpoint = $apiEndpoint;

        return $this;
    }

    /**
     * Get the API endpoint.
     *
     * @return string|null
     */
    public function getApiEndpoint(): ?string
    {
        return $this->apiEndpoint;
    }

    /**
     * Set the REST limit.
     *
     * @param int $limit
     *
     * @return self
     */
    public function setRestLimit(int $limit): self
    {
        $this->restLimit = $limit;

        return $this;
    }

    /**
     * Get the REST limit.
     *
     * @return int
     */
    public function getRestLimit(): int
    {
        return $this->restLimit;
    }
    

    /**
     * Set options for Guzzle.
     *
     * @param array $options
     *
     * @return self
     */
    public function setGuzzleOptions(array $options): self
    {
        $this->guzzleOptions = array_merge($this->guzzleOptions, $options);

        return $this;
    }

    /**
     * Get options for Guzzle.
     *
     * @return array
     */
    public function getGuzzleOptions(): array
    {
        return $this->guzzleOptions;
    }

    /**
     * Set a Guzzle handler.
     *
     * @param callable $handler
     *
     * @return self
     */
    public function setGuzzleHandler(callable $handler): self
    {
        $this->guzzleHandler = $handler;

        return $this;
    }

    /**
     * Get the Guzzle handler.
     *
     * @return callable|null
     */
    public function getGuzzleHandler(): ?callable
    {
        return $this->guzzleHandler;
    }
}

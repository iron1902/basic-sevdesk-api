<?php

namespace Iron1902\BasicSevdeskAPI\Clients;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Uri;
use Iron1902\BasicSevdeskAPI\Contracts\ClientAware;
use Iron1902\BasicSevdeskAPI\Contracts\LimitAccesser;
use Iron1902\BasicSevdeskAPI\Contracts\Respondable;
use Iron1902\BasicSevdeskAPI\Contracts\StateStorage;
use Iron1902\BasicSevdeskAPI\Contracts\TimeAccesser;
use Iron1902\BasicSevdeskAPI\Contracts\TimeDeferrer;
use Iron1902\BasicSevdeskAPI\Options;
use Iron1902\BasicSevdeskAPI\Traits\ResponseTransform;

/**
 * Base client class.
 */
abstract class AbstractClient implements TimeAccesser, LimitAccesser, ClientAware, Respondable
{
    use ResponseTransform;

    /**
     * The time store implementation.
     *
     * @var StateStorage
     */
    protected $tstore;

    /**
     * The limits store implementation.
     *
     * @var StateStorage
     */
    protected $lstore;

    /**
     * The time deferrer implementation.
     *
     * @var TimeDeferrer
     */
    protected $tdeferrer;

    /**
     * The Guzzle client.
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * The options.
     *
     * @var Options
     */
    protected $options;

    /**
     * Setup.
     *
     * @param StateStorage $tstore    The time store implementation.
     * @param StateStorage $lstore    The limits store implementation.
     * @param TimeDeferrer $tdeferrer The time deferrer implementation.
     *
     * @return self
     */
    public function __construct(StateStorage $tstore, StateStorage $lstore, TimeDeferrer $tdeferrer)
    {
        $this->tstore = $tstore;
        $this->lstore = $lstore;
        $this->tdeferrer = $tdeferrer;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUri(): Uri
    {
        return new Uri($this->options->getApiEndpoint());
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeDeferrer(): TimeDeferrer
    {
        return $this->tdeferrer;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeStore(): StateStorage
    {
        return $this->tstore;
    }

    /**
     * {@inheritdoc}
     */
    public function getLimitStore(): StateStorage
    {
        return $this->lstore;
    }

    /**
     * {@inheritdoc}
     */
    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(Options $options): void
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options
    {
        return $this->options;
    }
}

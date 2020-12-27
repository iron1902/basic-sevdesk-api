<?php

namespace Iron1902\BasicSevdeskAPI;

use Closure;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\Promise;
use GuzzleRetry\GuzzleRetryMiddleware;
use Iron1902\BasicSevdeskAPI\Clients\Rest;
use Iron1902\BasicSevdeskAPI\Contracts\ClientAware;
use Iron1902\BasicSevdeskAPI\Contracts\RestRequester;
use Iron1902\BasicSevdeskAPI\Contracts\SessionAware;
use Iron1902\BasicSevdeskAPI\Contracts\StateStorage;
use Iron1902\BasicSevdeskAPI\Contracts\TimeDeferrer;
use Iron1902\BasicSevdeskAPI\Deferrers\Sleep;
use Iron1902\BasicSevdeskAPI\Middleware\AuthRequest;
use Iron1902\BasicSevdeskAPI\Middleware\RateLimiting;
use Iron1902\BasicSevdeskAPI\Middleware\UpdateApiLimits;
use Iron1902\BasicSevdeskAPI\Middleware\UpdateRequestTime;
use Iron1902\BasicSevdeskAPI\Store\Memory;
use Iron1902\BasicSevdeskAPI\Traits\ResponseTransform;

/**
 * Basic sevDesk API for REST 
 */
class BasicSevdeskAPI implements ClientAware
{
    use ResponseTransform;

    /**
     * The Guzzle client.
     *
     * @var Client
     */
    protected $client;

    /**
     * The handler stack.
     *
     * @var HandlerStack
     */
    protected $stack;

    /**
     * The REST client.
     *
     * @var RestRequester
     */
    protected $restClient;

    /**
     * The library options.
     *
     * @var Options
     */
    protected $options;

    /**
     * Request timestamp for every new call.
     * Used for rate limiting.
     *
     * @var int
     */
    protected $requestTimestamp;

    /**
     * Constructor.
     *
     * @param Options           $options   The options for the library setup.
     * @param StateStorage|null $tstore    The time storer implementation to use for rate limiting.
     * @param StateStorage|null $lstore    The limits storer implementation to use for rate limiting.
     * @param TimeDeferrer|null $tdeferrer The time deferrer implementation to use for rate limiting.
     *
     * @return self
     */
    public function __construct(
        Options $options,
        ?StateStorage $tstore = null,
        ?StateStorage $lstore = null,
        ?TimeDeferrer $tdeferrer = null
    ) {
        // Setup REST clients
        $this->setupClients($tstore, $lstore, $tdeferrer);

        // Set the options
        $this->setOptions($options);

        // Create the stack and assign the middleware which attempts to fix redirects
        $this->stack = HandlerStack::create($this->getOptions()->getGuzzleHandler());
        $this
            ->addMiddleware(new AuthRequest($this), 'request:auth')
            ->addMiddleware(new RateLimiting($this), 'rate:limiting')
            ->addMiddleware(new UpdateRequestTime($this), 'time:update')
            ->addMiddleware(GuzzleRetryMiddleware::factory(), 'request:retry');

        // Create a default Guzzle client with our stack
        $this->setClient(
            new Client(array_merge(
                ['handler' => $this->stack],
                $this->getOptions()->getGuzzleOptions()
            ))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
        $this->getRestClient()->setClient($this->client);
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
        $this->getRestClient()->setOptions($this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * Sets the REST request client.
     *
     * @param RestRequester $client The client for REST.
     *
     * @return self
     */
    public function setRestClient(RestRequester $client): self
    {
        $this->restClient = $client;

        return $this;
    }

    /**
     * Get the REST client.
     *
     * @return RestRequester
     */
    public function getRestClient(): RestRequester
    {
        return $this->restClient;
    }

    /**
     * Add middleware to the handler stack.
     *
     * @param callable $callable Middleware function.
     * @param string   $name     Name to register for this middleware.
     *
     * @return self
     */
    public function addMiddleware(callable $callable, string $name = ''): self
    {
        $this->stack->push($callable, $name);

        return $this;
    }

    /**
     * Remove middleware to the handler stack.
     *
     * @param string $name Name to register for this middleware.
     *
     * @return self
     */
    public function removeMiddleware(string $name = ''): self
    {
        $this->stack->remove($name);

        return $this;
    }

    /**
     * Alias for REST method for backwards compatibility.
     *
     * @see rest
     */
    public function request()
    {
        return call_user_func_array(
            [$this, 'rest'],
            func_get_args()
        );
    }

    /**
     * @see Rest::request
     */
    public function rest(string $type, string $path, array $params = null, array $headers = [], bool $sync = true)
    {
        return $this->getRestClient()->request($type, $path, $params, $headers, $sync);
    }

    /**
     * Runs a request to the Sevdesk API (async).
     * Alias for `rest` with `sync` param set to `false`.
     *
     * @see rest
     */
    public function restAsync(string $type, string $path, array $params = null, array $headers = []): Promise
    {
        return $this->rest($type, $path, $params, $headers, false);
    }

    /**
     * Setup the REST clients.
     *
     * @param StateStorage|null $tstore    The time storer implementation to use for rate limiting.
     * @param StateStorage|null $lstore    The limits storer implementation to use for rate limiting.
     * @param TimeDeferrer|null $tdeferrer The time deferrer implementation to use for rate limiting.
     *
     * @return void
     */
    protected function setupClients(
        ?StateStorage $tstore = null,
        ?StateStorage $lstore = null,
        ?TimeDeferrer $tdeferrer = null
    ): void {
        // Base/default storage class if none provided
        $baseStorage = Memory::class;

        // Setup timestamp storage
        $restTstore = $tstore === null ? new $baseStorage() : clone $tstore;

        // Setup limits storage
        $restLstore = $lstore === null ? new $baseStorage() : clone $lstore;

        // Setup time deferrer
        $tdeferrer = $tdeferrer ?? new Sleep();

        // Setup REST and  clients
        $this->setRestClient(new Rest($restTstore, $restLstore, $tdeferrer));
    }
}

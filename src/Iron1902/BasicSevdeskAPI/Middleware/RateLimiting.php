<?php

namespace Iron1902\BasicSevdeskAPI\Middleware;

use Iron1902\BasicSevdeskAPI\BasicSevdeskAPI;
use Psr\Http\Message\RequestInterface;

/**
 * Handle basic request rate limiting for REST
 */
class RateLimiting extends AbstractMiddleware
{
    /**
     * Run.
     *
     * @param callable $handler
     *
     * @return callable
     */
    public function __invoke(callable $handler): callable
    {
        $self = $this;

        return function (RequestInterface $request, array $options) use ($self, $handler) {
            $this->handleRest($self->api);
            
            return $handler($request, $options);
        };
    }

    /**
     * Handle REST checks.
     *
     * @param BasicSevdeskAPI $api
     *
     * @return bool
     */
    protected function handleRest(BasicSevdeskAPI $api): bool
    {
        // Get the client
        $client = $api->getRestClient();
        $td = $client->getTimeDeferrer();
        $ts = $client->getTimeStore();

        $times = $ts->get();
        if (count($times) !== $api->getOptions()->getRestLimit()) {
            // Not at our limit yet, allow through without limiting
            return false;
        }

        // Determine if this call has passed the window
        $firstTime = end($times);
        $windowTime = $firstTime + 1;
        $currentTime = $td->getCurrentTime();

        if ($currentTime > $windowTime) {
            // Call is passed the window, reset and allow through without limiting
            $ts->reset();

            return false;
        }

        // Call is inside the window and not at the call limit, sleep until window can be reset
        $sleepTime = $windowTime - $currentTime;
        $td->sleep($sleepTime < 0 ? 0 : $sleepTime);
        $ts->reset();

        return true;
    }

}

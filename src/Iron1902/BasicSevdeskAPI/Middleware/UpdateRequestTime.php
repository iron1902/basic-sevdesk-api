<?php

namespace Iron1902\BasicSevdeskAPI\Middleware;

use Psr\Http\Message\RequestInterface;

/**
 * Update request times for calls.
 */
class UpdateRequestTime extends AbstractMiddleware
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
            // Get the client
            $api = $self->api;
            $client = $api->getRestClient() ;

            $client->getTimeStore()->push(
                $client->getTimeDeferrer()->getCurrentTime()
            );

            return $handler($request, $options);
        };
    }
}

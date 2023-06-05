<?php

namespace Iron1902\BasicSevdeskAPI\Middleware;

use Exception;
use Psr\Http\Message\RequestInterface;

/**
 * Ensures we have the proper request for private and public calls.
 * Also modifies issues with redirects.
 */
class AuthRequest extends AbstractMiddleware
{
    /**
     * Run.
     *
     * @param callable $handler
     *
     * @throws Exception For missing API key or password for private apps.
     *
     * @return callable
     */
    public function __invoke(callable $handler): callable
    {
        $self = $this;

        return function (RequestInterface $request, array $options) use ($self, $handler) {
            // Get the request URI
            $uri = $request->getUri();
            $apiKey = $self->api->getOptions()->getApiKey();
           
            $request = $request->withHeader(
                'Authorization',
                $apiKey
            );

            return $handler($request, $options);
        };
    }

}

<?php

namespace Iron1902\BasicSevdeskAPI\Clients;

use GuzzleHttp\Exception\RequestException;
use Iron1902\BasicSevdeskAPI\Contracts\RestRequester;
use Psr\Http\Message\ResponseInterface;

/**
 * REST handler.
 */
class Rest extends AbstractClient implements RestRequester
{


    /**
     * {@inheritdoc}
     */
    public function request(string $type, string $path, array $params = null, array $headers = [], bool $sync = true)
    {
        // Build URI

        $path = $this->getBaseUri()->getPath() . $path;

        $uri = $this->getBaseUri()->withPath($path);

        // Build the request parameters for Guzzle
        $guzzleParams = [];
        if ($params !== null) {
            $keys = array_keys($params);
            if (isset($keys[0]) && in_array($keys[0], ['query', 'json'])) {
                // Inputted type
                $guzzleParams = $params;
            } else {
                // Detect type
                $guzzleParams[strtoupper($type) === 'GET' ? 'query' : 'json'] = $params;
            }
        }

        // Add custom headers
        if (count($headers) > 0) {
            $guzzleParams['headers'] = $headers;
        }

        /**
         * Run the request as sync or async.
         */
        $requestFn = function () use ($sync, $type, $uri, $guzzleParams) {
            $fn = $sync ? 'request' : 'requestAsync';

            return $this->getClient()->{$fn}($type, $uri, $guzzleParams);
        };

        if ($sync === false) {
            // Async request
            $promise = $requestFn();

            return $promise->then([$this, 'handleSuccess'], [$this, 'handleFailure']);
        }

        // Sync request (default)
        try {
            return $this->handleSuccess($requestFn());
        } catch (RequestException $e) {
            return $this->handleFailure($e);
        }
    }

    /**
     * Handle success of response.
     *
     * @param ResponseInterface $resp
     *
     * @return array
     */
    public function handleSuccess(ResponseInterface $resp): array
    {


        // Return Guzzle response and JSON-decoded body
        return [
            'errors'     => false,
            'response'   => $resp,
            'status'     => $resp->getStatusCode(),
            'body'       => $this->toResponse($resp->getBody()),
            'timestamps' => $this->getTimeStore()->get(),
        ];
    }

    /**
     * Handle failure of response.
     *
     * @param RequestException $e
     *
     * @return array
     */
    public function handleFailure(RequestException $e): array
    {
        $resp = $e->getResponse();
        $body = null;
        $status = null;

        if ($resp) {
            // Get the body stream
            $rawBody = $resp->getBody();
            $status = $resp->getStatusCode();

            // Build the error object
            if ($rawBody !== null) {
                // Convert data to response
                $body = $this->toResponse($rawBody);
                $body = $body->hasErrors() ? $body->getErrors() : null;
            }
        }

        return [
            'errors'     => true,
            'response'   => $resp,
            'status'     => $status,
            'body'       => $body,
            'exception'  => $e,
            'timestamps' => $this->getTimeStore()->get(),
        ];
    }
}

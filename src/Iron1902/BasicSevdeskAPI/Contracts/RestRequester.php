<?php

namespace Iron1902\BasicSevdeskAPI\Contracts;

use Exception;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Uri;
use Iron1902\BasicSevdeskAPI\ResponseAccess;

/**
 * Reprecents REST client.
 */
interface RestRequester extends LimitAccesser, TimeAccesser, ClientAware
{
    /**
     * Runs a request to the Sevdesk API.
     *
     * @param string     $type    The type of request... GET, POST, PUT, DELETE.
     * @param string     $path    The Sevdesk API path... /Invoice.
     * @param array|null $params  Optional parameters to send with the request.
     * @param array      $headers Optional headers to append to the request.
     * @param bool       $sync    Optionally wait for the request to finish.
     *
     * @throws Exception
     *
     * @return array|Promise
     */
    public function request(string $type, string $path, array $params = null, array $headers = [], bool $sync = true);


    /**
     * Returns the base URI to use.
     *
     * @return Uri
     */
    public function getBaseUri(): Uri;

}

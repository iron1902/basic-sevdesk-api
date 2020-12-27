<?php

namespace Iron1902\BasicSevdeskAPI\Traits;

use Iron1902\BasicSevdeskAPI\ResponseAccess;
use Psr\Http\Message\StreamInterface;

/**
 * Handles transforming JSON response into response.
 */
trait ResponseTransform
{
    /**
     * @see Respondable::toResponse
     */
    public function toResponse(StreamInterface $body): ResponseAccess
    {
        $decoded = json_decode($body, true, 512, JSON_BIGINT_AS_STRING);

        return new ResponseAccess($decoded);
    }
}

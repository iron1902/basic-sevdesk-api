<?php

namespace Iron1902\BasicSevdeskAPI\Contracts;

use Iron1902\BasicSevdeskAPI\ResponseAccess;
use Psr\Http\Message\StreamInterface;

/**
 * Reprecents ability to respond to data tranformation.
 */
interface Respondable
{
    /**
     * Convert request response to response object.
     *
     * @return ResponseAccess
     */
    public function toResponse(StreamInterface $body): ResponseAccess;
}

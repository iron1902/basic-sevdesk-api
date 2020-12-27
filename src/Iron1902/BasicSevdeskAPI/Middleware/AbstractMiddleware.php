<?php

namespace Iron1902\BasicSevdeskAPI\Middleware;

use Iron1902\BasicSevdeskAPI\BasicSevdeskAPI;

abstract class AbstractMiddleware
{
    /**
     * The API instance.
     *
     * @var BasicSevdeskAPI
     */
    protected $api;

    /**
     * Setup.
     *
     * @param BasicSevdeskAPI $api The API instance.
     *
     * @return self
     */
    public function __construct(BasicSevdeskAPI $api)
    {
        $this->api = $api;
    }
}

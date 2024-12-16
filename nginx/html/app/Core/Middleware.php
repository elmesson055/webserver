<?php

namespace App\Core;

abstract class Middleware {
    /**
     * Handle the middleware request
     * 
     * @param array $params Route parameters
     * @return bool|string Returns true to continue or a response string to halt
     */
    abstract public function handle($params);
}

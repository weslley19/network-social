<?php

namespace core;

use \core\RouterBase;

class Router extends RouterBase
{
    /**
     *
     * @var [type]
     */
    public $routes;

    /**
     *
     * @param [type] $endpoint
     * @param [type] $trigger
     * @return void
     */
    public function get($endpoint, $trigger): void
    {
        $this->routes['get'][$endpoint] = $trigger;
    }

    /**
     *
     * @param [type] $endpoint
     * @param [type] $trigger
     * @return void
     */
    public function post($endpoint, $trigger): void
    {
        $this->routes['post'][$endpoint] = $trigger;
    }

    /**
     *
     * @param [type] $endpoint
     * @param [type] $trigger
     * @return void
     */
    public function put($endpoint, $trigger): void
    {
        $this->routes['put'][$endpoint] = $trigger;
    }

    /**
     *
     * @param [type] $endpoint
     * @param [type] $trigger
     * @return void
     */
    public function delete($endpoint, $trigger): void
    {
        $this->routes['delete'][$endpoint] = $trigger;
    }
}

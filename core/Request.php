<?php

namespace core;

use src\Config;

class Request
{
    /**
     *
     * @return string
     */
    public static function getUrl(): string
    {
        $url = filter_input(INPUT_GET, 'request');
        $url = str_replace(Config::BASE_DIR, '', $url);
        return '/' . $url;
    }

    /**
     *
     * @return string
     */
    public static function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}

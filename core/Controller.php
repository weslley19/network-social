<?php

namespace core;

use \src\Config;

class Controller
{
    /**
     * 
     * @param [type] $url
     * @return void
     */
    protected function redirect($url): void
    {
        header("Location: " . $this->getBaseUrl() . $url);
        exit;
    }

    /**
     *
     * @return string
     */
    private function getBaseUrl(): string
    {
        $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $base .= $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') {
            $base .= ':' . $_SERVER['SERVER_PORT'];
        }
        $base .= Config::BASE_DIR;

        return $base;
    }

    /**
     *
     * @param [type] $folder
     * @param [type] $viewName
     * @param array $viewData
     * @return void
     */
    private function _render($folder, $viewName, $viewData = []): void
    {
        if (file_exists('../src/views/' . $folder . '/' . $viewName . '.php')) {
            extract($viewData);
            $render = fn ($vN, $vD = []) => $this->renderPartial($vN, $vD);
            $base = $this->getBaseUrl();
            require '../src/views/' . $folder . '/' . $viewName . '.php';
        }
    }

    /**
     *
     * @param [type] $viewName
     * @param array $viewData
     * @return void
     */
    private function renderPartial($viewName, $viewData = []): void
    {
        $this->_render('partials', $viewName, $viewData);
    }

    /**
     *
     * @param [type] $viewName
     * @param array $viewData
     * @return void
     */
    public function render($viewName, $viewData = []): void
    {
        $this->_render('pages', $viewName, $viewData);
    }
}

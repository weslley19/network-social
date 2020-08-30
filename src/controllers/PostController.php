<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class PostController extends Controller
{
    /** @var [type] */
    private $loggedUser;

    /**
     * Connect construct
     */
    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        if ($this->loggedUser === false) {
            $this->redirect('/login');
        }
    }

    /**
     *
     * @return void
     */
    public function new(): void
    {
        $body = trim(filter_input(INPUT_POST, 'body'));

        if ($body) {
            PostHandler::addPost(
                $this->loggedUser->id,
                'text',
                $body
            );
        }
        $this->redirect('/');
    }
}

<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use src\handlers\PostHandler;

class HomeController extends Controller
{
    /**
     *
     * @var [type]
     */
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
    public function index(): void
    {
        $page = filter_input(INPUT_GET, 'page');

        $feed = PostHandler::getHomeFeed(
            $this->loggedUser->id,
            $page,
        );

        $this->render('home', [
            'loggedUser' => $this->loggedUser,
            'feed' => $feed,
        ]);
    }
}

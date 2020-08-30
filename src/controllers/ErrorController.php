<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;

class ErrorController extends Controller
{
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
    public function index()
    {
        // pegar os dados do user
        $user = UserHandler::getUser($this->loggedUser->id, true);

        $flash = '';
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('404', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'flash' => $flash
        ]);
    }
}

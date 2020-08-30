<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;

class SearchController extends Controller
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
     * Se receber um atributo é pq é de outra pessoa. Caso não, é o perfil de logado
     *
     * @param array $atts
     * @return void
     */
    public function index(array $atts = []): void
    {
        $searchTerm = filter_input(INPUT_GET, 's');

        if (empty($searchTerm)) {
            $this->redirect('/');
        }

        // usuario que quer buscar
        $users = UserHandler::searchUser($searchTerm);

        // sempre com a key e o value
        $this->render('search', [
            'loggedUser' => $this->loggedUser,
            'searchTerm' => $searchTerm,
            'users' => $users
        ]);
    }
}

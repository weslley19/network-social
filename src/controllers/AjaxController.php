<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class AjaxController extends Controller
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
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Usuário não logado']);
            exit;
        }
    }

    /**
     *
     * @param array $atts
     * @return void
     */
    public function like($atts = []): void
    {
        $id = $atts['id'];

        if (PostHandler::isLike($id, $this->loggedUser->id)) {
            PostHandler::deleteLike($id, $this->loggedUser->id);
        } else {
            PostHandler::addLike($id, $this->loggedUser->id);
        }
    }
}

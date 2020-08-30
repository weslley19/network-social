<?php

namespace src\controllers;

use \core\Controller;
use DateTime;
use \src\handlers\UserHandler;
use src\handlers\PostHandler;
use src\models\UserRelation;

class ProfileController extends Controller
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
        //paginação
        $page = filter_input(INPUT_GET, 'page');

        $id = $this->loggedUser->id;

        if (!empty($atts['id'])) {
            $id = $atts['id'];
        }

        $user = UserHandler::getUser($id, true);
        if (!$user) {
            $this->redirect('/');
        }

        // idade do usuarios
        $dateFrom = new DateTime($user->birthdate);
        $dateTo = new DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        // feed do proprio usuario
        $feed = PostHandler::getUserFeed(
            $id,
            $page,
            $this->loggedUser->id
        );

        // verificar se eu estou seguindo 
        $isFollowing = false;
        if ($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        // sempre com a key e o value
        $this->render('profile', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'feed' => $feed,
            'isFollowing' => $isFollowing
        ]);
    }

    /**
     *
     * @param [type] $atts
     * @return void
     */
    public function follow($atts): void
    {
        $to = intval($atts['id']);

        if (UserHandler::idExists($to)) {
            if (UserHandler::isFollowing($this->loggedUser->id, $to)) {
                // desseguir
                UserHandler::unfollow($this->loggedUser->id, $to);
            } else {
                //seguir
                UserHandler::follow($this->loggedUser->id, $to);
            }
        }

        $this->redirect('/perfil/' . $to);
    }

    /**
     *
     * @param array $atts
     * @return void
     */
    public function friends($atts = []): void
    {
        // verifico se existe id
        $id = $this->loggedUser->id;
        if (!empty($atts['id'])) {
            $id = $atts['id'];
        }

        // pego o id aqui
        $user = UserHandler::getUser($id, true);
        if (!$user) {
            $this->redirect('/');
        }

        // idade do usuarios
        $dateFrom = new DateTime($user->birthdate);
        $dateTo = new DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        // verificar se eu estou seguindo 
        $isFollowing = false;
        if ($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        // sempre com a key e o value
        $this->render('profile_friends', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);
    }

    /**
     *
     * @param array $atts
     * @return void
     */
    public function photos($atts = []): void
    {
        // verifico se existe id
        $id = $this->loggedUser->id;
        if (!empty($atts['id'])) {
            $id = $atts['id'];
        }

        // pego o id aqui
        $user = UserHandler::getUser($id, true);
        if (!$user) {
            $this->redirect('/');
        }

        // idade do usuarios
        $dateFrom = new DateTime($user->birthdate);
        $dateTo = new DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        // verificar se eu estou seguindo 
        $isFollowing = false;
        if ($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        // sempre com a key e o value
        $this->render('profile_photos', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);
    }
}

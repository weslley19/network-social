<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;

class LoginController extends Controller
{
    /**
     *
     * @return void
     */
    public function signin(): void
    {
        $flash = '';
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('signin', [
            'flash' => $flash
        ]);
    }

    /**
     *
     * @return void
     */
    public function signinAction(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if ($email && $password) {
            $token = UserHandler::verifyLogin($email, $password);
            if ($token) {
                $_SESSION['token'] = $token;
                $this->redirect('/');
            } else {
                $_SESSION['flash'] = 'E-mail e / ou senha não conferem';
                $this->redirect('/login');
            }
        } else {
            $_SESSION['flash'] = 'Preencha todos os dados';
            $this->redirect('/login');
        }
    }

    /**
     *
     * @return void
     */
    public function signup(): void
    {
        $flash = '';
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('signup', [
            'flash' => $flash
        ]);
    }

    /**
     *
     * @return void
     */
    public function signupAction(): void
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $birthDate = filter_input(INPUT_POST, 'birthdate');

        if ($name && $email && $password && $birthDate) {
            $birthDate = explode('/', $birthDate);
            if (count($birthDate) != 3) {
                $_SESSION['flash'] = 'Data de nascimento inválida';
                $this->redirect('/cadastro');
            }

            $birthDate = $birthDate[2] . '-' . $birthDate[1] . '-' . $birthDate[0];

            if (strtotime($birthDate) === false) {
                $_SESSION['flash'] = 'Data de nascimento inválida';
                $this->redirect('/cadastro');
            }

            if (UserHandler::emailExists($email) === false) {
                $token = UserHandler::addUser($name, $email, $password, $birthDate);
                $_SESSION['token'] = $token;
                $this->redirect('/');
            } else {
                $_SESSION['flash'] = 'E-mail já cadastrado';
                $this->redirect('/cadastro');
            }
        } else {
            $_SESSION['flash'] = 'Preencha todos dados';
            $this->redirect('/cadastro');
        }
    }

    /**
     *
     * @return void
     */
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/');
    }
}

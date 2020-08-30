<?php

namespace src\controllers;

use \core\Controller;
use \DateTime;
use \src\handlers\UserHandler;

class ConfigController extends Controller
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
        // pegar os dados do user
        $user = UserHandler::getUser($this->loggedUser->id, true);

        $flash = '';
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('configuration', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'flash' => $flash
        ]);
    }

    /**
     * Atualizar os dados
     *
     * @return void
     */
    public function save()
    {
        $avatar = filter_input(INPUT_POST, 'avatar');
        $cover = filter_input(INPUT_POST, 'cover');
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRIPPED);
        $birthDate = filter_input(INPUT_POST, 'birthdate');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRIPPED);
        $work = filter_input(INPUT_POST, 'work', FILTER_SANITIZE_STRIPPED);
        $password = filter_input(INPUT_POST, 'password');
        $passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm');

        if ($password) {
            $user = UserHandler::getUser($this->loggedUser->id);

            /**
             * Se não informar o valor o campo fica vazio, caso informe o campo recebe valor
             */ 
            (empty($name) ? $user->name : $name);
            (empty($city) ? $user->city : $city);
            (empty($work) ? $user->work : $work);

            // validação de data de nascimento
            $birthDate = explode('/', $birthDate);
            if (count($birthDate) != 3) {
                $_SESSION['flash'] = 'Data de nascimento inválida';
                $this->redirect('/configuracoes');
            }

            $birthDate = $birthDate[2] . '-' . $birthDate[1] . '-' . $birthDate[0];

            if (strtotime($birthDate) === false) {
                $_SESSION['flash'] = 'Data de nascimento inválida';
                $this->redirect('/configuracoes');
            }

            (empty($birthDate) ? $user->birthdate : $birthDate);

            // validação do email
            (empty($email) ? $user->email : $email);


            // validação da senha
            if ($password === $passwordConfirm) {
                $password = $password;
            } else {
                $_SESSION['flash'] = 'Senhas não conferem';
                $this->redirect('/configuracoes');
            }

            // tipo de arquivos aceitos
            $allowedType = [
                'image/jpeg',
                'image/jpg',
                'image/png'
            ];

            // avatar
            if ($avatar) {
                if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
                    $newAvatar = $_FILES['avatar'];

                    if (in_array($newAvatar['type'], $allowedType)) {
                        $avatarName = $this->cutImage($newAvatar, 200, 200, 'media/avatars');
                        $avatar = $avatarName;
                    }
                }
            } else {
                $avatar = $user->avatar;
            }
            // cover
            if ($cover) {
                if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])) {
                    $newCover = $_FILES['cover'];

                    if (in_array($newCover['type'], $allowedType)) {
                        $coverName = $this->cutImage($newCover, 850, 310, 'media/covers');
                        $cover = $coverName;
                    }
                }
            } else {
                $cover = $user->cover;
            }


            // update dos dados
            UserHandler::updateUser($this->loggedUser->id, $email, $password, $name, $birthDate, $city, $work, $avatar, $cover, $passwordConfirm);

            $_SESSION['flash'] = 'Dados salvos com sucesso';
        } else {
            $_SESSION['flash'] = 'Precisa informar a senha';
        }

        $this->redirect('/configuracoes');
    }

    /**
     * Recorta imagem
     *
     * @param [type] $file
     * @param [type] $w
     * @param [type] $h
     * @param [type] $folder
     * @return string
     */
    private function cutImage($file, $w, $h, $folder): string
    {
        list($widthOrigin, $heigthOrigin) = getimagesize($file['tmp_name']);

        $ratio = $widthOrigin / $heigthOrigin;

        $newWidth = $w;
        $newHeigth = $newWidth / $ratio;

        if ($newHeigth < $h) {
            $newHeigth = $h;
            $newWidth = $newHeigth * $ratio;
        }

        $x = $w - $newWidth;
        $y = $h - $newHeigth;
        $x = $x < 0 ? $x / 2 : $x;
        $y = $y < 0 ? $y / 2 : $y;

        $imageFinal = imagecreatetruecolor($w, $h);
        switch ($file['type']) {
            case 'image/jpg':
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
                break;
        }

        imagecopyresampled(
            $imageFinal,
            $image,
            $x,
            $y,
            0,
            0,
            $newWidth,
            $newHeigth,
            $widthOrigin,
            $heigthOrigin,
        );

        $fileName = md5(time() . rand(0, 9999)) . '.jpg';

        imagejpeg($imageFinal, $folder . '/' . $fileName);

        return $fileName;
    }
}

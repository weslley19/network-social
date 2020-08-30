<?php

namespace src\handlers;

use src\models\User;
use src\models\UserRelation;
use src\handlers\PostHandler;

class UserHandler
{
    /**
     *
     * @return object|bool
     */
    public static function checkLogin()
    {
        if (!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];

            $data = User::select()->where('token', $token)->one();

            if (count($data) > 0) {
                $loggedUser = new User();
                $loggedUser->id = $data['id'];
                $loggedUser->email = $data['email'];
                $loggedUser->password = $data['password'];
                $loggedUser->name = $data['name'];
                $loggedUser->birthdate = $data['birthdate'];
                $loggedUser->city = $data['city'];
                $loggedUser->work = $data['work'];
                $loggedUser->avatar = $data['avatar'];
                $loggedUser->cover = $data['cover'];

                return $loggedUser;
            }
        }

        return false;
    }

    /**
     *
     * @param [type] $email
     * @param [type] $password
     * @return string|bool
     */
    public static function verifyLogin($email, $password): string
    {
        $user = User::select()->where('email', $email)->one();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $token = md5(time() . rand(9999) . time());

                User::update()
                    ->set('token', $token)
                    ->where('email', $email)
                    ->execute();

                return $token;
            }
        }

        return false;
    }

    /**
     *
     * @param [type] $id
     * @return bool
     */
    public static function idExists($id): bool
    {
        $user = User::select()->where('id', $id)->one();
        return $user ? true : false;
    }

    /**
     *
     * @param [type] $email
     * @return bool
     */
    public static function emailExists($email): bool
    {
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }

    /**
     *
     * @param [type] $id
     * @param boolean $full
     * @return object
     */
    public static function getUser($id, $full = false): object
    {
        $data = User::select()->where('id', $id)->one();

        // Se data trazer algo, mostre
        if ($data) {
            $user = new User();
            $user->id = $data['id'];
            $user->email = $data['email'];
            $user->name = $data['name'];
            $user->password = $data['password'];
            $user->birthdate = $data['birthdate'];
            $user->city = $data['city'];
            $user->work = $data['work'];
            $user->avatar = $data['avatar'];
            $user->cover = $data['cover'];

            if ($full) {
                $user->followers = [];
                $user->following = [];
                $user->photos = [];

                // followers
                $followers = UserRelation::select()->where('user_to', $id)->get();

                foreach ($followers as $follower) {
                    $userData = User::select()->where('id', $follower['user_from'])->one();

                    $newUser = new User();
                    $newUser->id = $userData['id'];
                    $newUser->avatar = $userData['avatar'];
                    $newUser->name = $userData['name'];

                    $user->followers[] = $newUser;
                }

                //following
                $following = UserRelation::select()->where('user_from', $id)->get();

                foreach ($following as $follower) {
                    $userData = User::select()->where('id', $follower['user_to'])->one();

                    $newUser = new User();
                    $newUser->id = $userData['id'];
                    $newUser->avatar = $userData['avatar'];
                    $newUser->name = $userData['name'];

                    $user->following[] = $newUser;
                }

                //photos
                $user->photos = PostHandler::getPhotosFrom($id);
            }

            return $user;
        }

        // Se não trazer nada
        return false;
    }

    /**
     *
     * @param [type] $name
     * @param [type] $email
     * @param [type] $password
     * @param [type] $birthDate
     * @return string
     */
    public static function addUser($name, $email, $password, $birthDate): string
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time() . rand(9999) . time());

        User::insert([
            'email' => $email,
            'password' => $hash,
            'name' => $name,
            'birthdate' => $birthDate,
            'token' => $token
        ])->execute();

        return $token;
    }

    /**
     *
     * @param [type] $id
     * @param [type] $email
     * @param [type] $password
     * @param [type] $name
     * @param [type] $birthDate
     * @param [type] $city
     * @param [type] $work
     * @param [type] $avatar
     * @param [type] $cover
     * @return void
     */
    public static function updateUser($id, $email, $password, $name, $birthDate, $city, $work, $avatar, $cover): void
    {
        //Gera o hash da senha, caso a senha mude
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            User::update()
                ->set('email', $email)
                ->set('password', $hash)
                ->set('name', $name)
                ->set('birthdate', $birthDate)
                ->set('city', $city)
                ->set('work', $work)
                ->set('avatar', $avatar)
                ->set('cover', $cover)
                ->where('id', $id)
                ->execute();
        }
    }

    /**
     *
     * @param [type] $from
     * @param [type] $to
     * @return boolean
     */
    public static function isFollowing($from, $to): bool
    {
        $data = UserRelation::select()
            ->where('user_from', $from)
            ->where('user_to', $to)
            ->one();

        if ($data) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param [type] $from
     * @param [type] $to
     * @return void
     */
    public static function follow($from, $to): void
    {
        UserRelation::insert([
            'user_from' => $from,
            'user_to' => $to
        ])->execute();
    }

    /**
     *
     * @param [type] $from
     * @param [type] $to
     * @return void
     */
    public static function unfollow($from, $to): void
    {
        UserRelation::delete()
            ->where('user_from', $from)
            ->where('user_to', $to)
            ->execute();
    }

    /**
     *
     * @param [type] $term
     * @return array
     */
    public static function searchUser($term): array
    {
        $users = [];
        $data = User::select()->where('name', 'like', '%' . $term . '%')->get();

        if ($data) {
            foreach ($data as $user) {
                $newUser = new User();
                $newUser->id = $user['id'];
                $newUser->name = $user['name'];
                $newUser->avatar = $user['avatar'];

                $users[] = $newUser;
            }
        }

        return $users;
    }
}

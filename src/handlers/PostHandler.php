<?php

namespace src\handlers;

use src\models\Post;
use src\models\PostLike;
use src\models\PostComment;
use src\models\User;
use src\models\UserRelation;
use src\handlers\UserHandler;

class PostHandler
{
    /**
     *
     * @param [type] $idUser
     * @param [type] $type
     * @param [type] $body
     * @return void
     */
    public static function addPost($idUser, $type, $body): void
    {
        if (!empty($idUser) && !empty($body)) {
            Post::insert([
                'id_user' => $idUser,
                'type' => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'body' => $body
            ])->execute();
        }
    }

    /**
     * Sem o static pois não vai ser usada em outro lugar
     *
     * @param [type] $postsList
     * @param [type] $loggedUserId
     * @return array
     */
    public static function postListToObject($postsList, $loggedUserId): array
    {
        // transformar resultados em objetos e percorre os resultados do array
        $posts = [];
        foreach ($postsList as $postItem) {
            $newPost = new Post();
            $newPost->id = $postItem['id'];
            $newPost->type = $postItem['type'];
            $newPost->created_at = $postItem['created_at'];
            $newPost->body = $postItem['body'];
            $newPost->mine = false;

            if ($postItem['id_user'] == $loggedUserId) {
                $newPost->mine = true;
            }

            // preencher informações adicionais do post com nome e foto
            $newUser = User::select()->where('id', $postItem['id_user'])->one();
            $newPost->user = new User();
            $newPost->user->id = $newUser['id'];
            $newPost->user->name = $newUser['name'];
            $newPost->user->avatar = $newUser['avatar'];

            // informação sobre likes
            $likes = PostLike::select()->where('id_post', $postItem['id'])->get();

            $newPost->likeCount = count($likes);
            $newPost->liked = self::isLike($postItem['id'], $loggedUserId);


            // informação sobre comentarios
            $newPost->comments = PostComment::select()->where('id_post', $postItem['id'])->get();
            foreach ($newPost->comments as $key => $comments) {
                $newPost->comments[$key]['user'] = User::select()->where('id', $comments['id_user'])->one();
            }

            // joga no array
            $posts[] = $newPost;
        }

        return $posts;
    }

    /**
     *
     * @param [type] $id
     * @param [type] $loggedUserId
     * @return boolean
     */
    public static function isLike($id, $loggedUserId): bool
    {
        $myLikes = PostLike::select()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
            ->get();

        if (count($myLikes) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param [type] $id
     * @param [type] $loggedUserId
     * @return void
     */
    public static function deleteLike($id, $loggedUserId): void
    {
        PostLike::delete()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
            ->execute();
    }

    /**
     *
     * @param [type] $id
     * @param [type] $loggedUserId
     * @return void
     */
    public static function addLike($id, $loggedUserId): void
    {
        PostLike::insert([
            'id_post' => $id,
            'id_user' => $loggedUserId,
            'created_at' => date('Y-m-d H:i:s')
        ])->execute();
    }

    /**
     *
     * @param [type] $idUser
     * @param [type] $page
     * @param [type] $loggedUserId
     * @return array
     */
    public static function getUserFeed($idUser, $page, $loggedUserId): array
    {
        $perPage = 2;

        // pega os posts ordenados pela data
        $postsList = Post::select()
            ->where('id_user', $idUser)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
            ->get();

        // quantidade de páginas
        $total = Post::select()
            ->where('id_user', $idUser)
            ->count();

        // arrendonda pra cima
        $pageCount = ceil($total / $perPage);


        // chamar a função ajudadora
        $posts = self::postListToObject($postsList, $loggedUserId);

        // retorno resultado
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

    /**
     *
     * @param [type] $idUser
     * @param [type] $page
     * @return array
     */
    public static function getHomeFeed($idUser, $page): array
    {
        $perPage = 2;

        // pega lista dos usuarios que eu sigo
        $userList = UserRelation::select()->where('user_from', $idUser)->get();
        $users = [];

        foreach ($userList as $userItem) {
            $users[] = $userItem['user_to'];
        }

        $users[] = $idUser;

        // pega os posts ordenados pela data
        $postsList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
            ->get();

        // quantidade de páginas
        $total = Post::select()
            ->where('id_user', 'in', $users)
            ->count();

        // arrendonda pra cima
        $pageCount = ceil($total / $perPage);


        // chamar a função ajudadora
        $posts = self::postListToObject($postsList, $idUser);

        // retorno resultado
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

    /**
     *
     * @param [type] $idUser
     * @return array
     */
    public static function getPhotosFrom($idUser): array
    {
        $photos = [];
        $photosData = Post::select()
            ->where('id_user', $idUser)
            ->where('type', 'photo')
            ->get();

        foreach ($photosData as $photo) {
            $newPost = new Post();
            $newPost->id = $photo['id'];
            $newPost->type = $photo['type'];
            $newPost->created_at = $photo['created_at'];
            $newPost->body = $photo['body'];

            $photos[] = $newPost;
        }

        return $photos;
    }
}

<?= $render('header', ['loggedUser' => $loggedUser, 'user' => $user]); ?>

<!-- sessão -->
<?php if (!empty($flash)) : ?>
    <div class="flash"><?= $flash; ?></div>
<?php endif; ?>

<section class="container main">
    <?= $render('sidebar', ['activeMenu' => 'config']); ?>

    <section class="feed">

        <div class="main_content">
            <div class="main_content_title mt-10">
                <h1>Configurações da conta</h1>
            </div>
            <div class="main_content_form mt-10">
                <form class="main_content_form_config" method="post" enctype="multipart/form-data">
                    <div class="main_content_form_files">
                        Novo avatar: <br>
                        <input class="input" type="file" name="avatar"> <br><br>
                        <img class="image_edit" src="<?= $base; ?>/media/avatars/<?= $user->avatar; ?>" alt=""> <br><br>
                        Nova capa: <br>
                        <input type="file" name="cover">
                        <img class="image_edit" src="<?= $base; ?>/media/covers/<?= $user->cover; ?>" alt=""> <br><br>
                    </div>

                    <div class="border"></div>

                    <div class="main_content_form_input">
                        Nome: <br>
                        <input class="input" type="text" name="name" value="<?= $user->name; ?>"> <br><br>
                        Data de Nascimento: <br>
                        <input type="text" name="birthdate" id="birthdate" value="<?= date('d/m/Y', strtotime($user->birthdate)); ?>"> <br><br>
                        E-mail: <br>
                        <input type="email" name="email" value="<?= $user->email; ?>"> <br><br>
                        Cidade: <br>
                        <input type="text" name="city" value="<?= $user->city; ?>"> <br><br>
                        Trabalho: <br>
                        <input type="text" name="work" value="<?= $user->work; ?>"> <br><br>
                    </div>

                    <div class="border"></div>

                    <div class="main_content_form_input">
                        Nova senha: <br>
                        <input type="password" name="password"> <br>
                        <small class="pass_small">* Mínimo de 3 caracteres</small> <br><br>
                        Repetir senha: <br>
                        <input type="password" name="passwordConfirm"> <br>
                        <small class="pass_small">* Mínimo de 3 caracteres</small> <br><br>
                    </div>
                    <input class="button_configuration" type="submit" value="Salvar">
                </form>
            </div>
        </div>

    </section>
</section>

<?= $render('footer'); ?>
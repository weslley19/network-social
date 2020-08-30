<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Login - DevsBook</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1" />
    <link rel="stylesheet" href="<?= $base; ?>/assets/css/login.css" />
</head>

<body>
    <header>
        <div class="container">
            <a href="<?php $base ?>">
                <h1 class="logo">Devsbook</h1>
            </a>
        </div>
    </header>
    <section class="container main">
        <form method="POST">

            <?php if (!empty($flash)) : ?>
                <div class="flash"><?= $flash; ?></div>
            <?php endif; ?>

            <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />

            <input placeholder="Digite sua senha" class="input" type="password" name="password" />

            <input class="button" type="submit" value="Acessar o sistema" />

            <a href="<?= $base; ?>/cadastro">Ainda não tem conta? Cadastre-se</a>
        </form>
        <p class="copy">por Weslley Oliveira</p>
    </section>
</body>

</html>
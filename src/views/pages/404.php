<?= $render('header', ['loggedUser' => $loggedUser]); ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $base; ?>/assets/css/style.css" />
    <title>Devsbook</title>
</head>

<body>
    <main class="main_content_404">
        <img src="<?= $base; ?>/assets/images/404.1.webp" alt="">

        <h1 class="main_content_title_404">Esta página não esta disponível</h1>

        <p class="mains_content_content_404">
            O link pode não estar funcionando ou a Página pode ter <br> sido removida. Verifique se o link que você está tentando <br> abrir está correto.
        </p>

        <a class="button_404" href="<?= $base; ?>/">Voltar para o Feed</a>
    </main>
</body>

</html>
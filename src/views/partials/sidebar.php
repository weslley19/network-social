<aside class="mt-10">
    <nav class="sidebar menu">
        <a href="<?= $base; ?>">
            <div class="menu-item <?= ($activeMenu == 'home') ? 'active' : ''; ?>">
                <div class="menu-item-icon">
                    <img src="<?= $base; ?>/assets/images/home-run.png" width="18" height="18" />
                </div>
                <div class="menu-item-text">
                    Home
                </div>
            </div>
        </a>
        <a href="<?= $base; ?>/perfil">
            <div class="menu-item <?= ($activeMenu == 'profile') ? 'active' : ''; ?>">
                <div class="menu-item-icon">
                    <img src="<?= $base; ?>/assets/images/user.png" width="18" height="18" />
                </div>
                <div class="menu-item-text">
                    Meu Perfil
                </div>
            </div>
        </a>
        <a href="<?= $base; ?>/amigos">
            <div class="menu-item <?= ($activeMenu == 'friends') ? 'active' : ''; ?>">
                <div class="menu-item-icon">
                    <img src="<?= $base; ?>/assets/images/friends.png" width="18" height="18" />
                </div>
                <div class="menu-item-text">
                    Amigos
                </div>
            </div>
        </a>
        <a href="<?= $base; ?>/fotos">
            <div class="menu-item <?= ($activeMenu == 'photos') ? 'active' : ''; ?>">
                <div class="menu-item-icon">
                    <img src="<?= $base; ?>/assets/images/photo.png" width="18" height="18" />
                </div>
                <div class="menu-item-text">
                    Fotos
                </div>
            </div>
        </a>
        <div class="menu-splitter"></div>
        <a href="<?= $base; ?>/configuracoes">
            <div class="menu-item <?= ($activeMenu == 'config') ? 'active' : ''; ?>">
                <div class="menu-item-icon">
                    <img src="<?= $base; ?>/assets/images/settings.png" width="18" height="18" />
                </div>
                <div class="menu-item-text">
                    Configurações
                </div>
            </div>
        </a>
        <a href="<?= $base; ?>/sair">
            <div class="menu-item">
                <div class="menu-item-icon">
                    <img src="<?= $base; ?>/assets/images/power.png" width="18" height="18" />
                </div>
                <div class="menu-item-text">
                    Sair
                </div>
            </div>
        </a>
    </nav>
</aside>
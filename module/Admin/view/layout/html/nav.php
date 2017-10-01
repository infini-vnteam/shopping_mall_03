<header class="main-header">
    <a class="logo" href="<?= $this->url('home') ?>">
        <span class="logo-mini"><b>I</b>S</span>
        <span class="logo-lg"><b>Infini</b>Shop</span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?= $this->url('home') ?>">User mode</a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><?php echo $this->user['name'] ?></span>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="/logout">Logout</a>
                            </li>
                        </ul>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

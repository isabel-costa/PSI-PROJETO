<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Eventos', 'header' => true, 'visible' => Yii::$app->user->can('createEvents')],
                    ['label' => 'Ver Eventos', 'url' => ['..\evento\index'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->can('createEvents')],
                    ['label' => 'Criar Eventos',  'icon' => 'file-code', 'url' => ['..\evento\create'], 'visible' => Yii::$app->user->can('createEvents')],

                    ['label' => 'Utilizadores', 'header' => true, 'visible' => Yii::$app->user->can('createUsers')],
                    ['label' => 'Ver Users', 'url' => ['..\user\index'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->can('createUsers')],
                    ['label' => 'Criar User',  'icon' => 'file-code', 'url' => ['..\user\create'], 'visible' => Yii::$app->user->can('createUsers')],

                    ['label' => 'Categorias', 'header' => true, 'visible' => Yii::$app->user->can('createUsers')],
                    ['label' => 'Ver Categorias', 'url' => ['..\categoria\index'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->can('createUsers')],
                    ['label' => 'Criar Categorias',  'icon' => 'file-code', 'url' => ['..\categoria\create'], 'visible' => Yii::$app->user->can('createUsers')],

                    ['label' => 'Locais', 'header' => true, 'visible' => Yii::$app->user->can('createUsers')],
                    ['label' => 'Ver Locais', 'url' => ['..\local\index'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->can('createUsers')],
                    ['label' => 'Criar Locais',  'icon' => 'file-code', 'url' => ['..\local\create'], 'visible' => Yii::$app->user->can('createUsers')],

                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
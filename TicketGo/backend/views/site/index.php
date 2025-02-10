<?php
$this->title = 'Dashboard';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">

        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('organizer')): ?>

            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => 'Eventos',
                'theme' => 'primary',
                'linkUrl' => \yii\helpers\Url::to(['evento/index']),
                ]); ?>
            <?php endif; ?>

        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?php if (Yii::$app->user->can('admin')): ?>

            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => 'Users',
                'theme' => 'primary',
                'linkUrl' => \yii\helpers\Url::to(['user/index']),
            ]); ?>
            <?php endif; ?>

        </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?php if (Yii::$app->user->can('admin')): ?>
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => 'Categorias',
                'theme' => 'primary',
                'linkUrl' => \yii\helpers\Url::to(['categoria/index']),
            ]); ?>
        <?php endif; ?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?php if (Yii::$app->user->can('createUsers')): ?>

        <?= \hail812\adminlte\widgets\SmallBox::widget([
            'title' => 'Locais',
            'theme' => 'primary',

            'linkUrl' => \yii\helpers\Url::to(['local/index']),
        ]); ?>
        <?php endif; ?>

    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <?php if (Yii::$app->user->can('createUsers')): ?>

            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => 'Metodos Pagamento',
                'theme' => 'primary',

                'linkUrl' => \yii\helpers\Url::to(['metodo-pagamento/index']),
            ]); ?>
        <?php endif; ?>

    </div>

</div>
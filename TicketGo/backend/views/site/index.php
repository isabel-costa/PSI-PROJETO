<?php
use hail812\adminlte\widgets\Alert;
use hail812\adminlte\widgets\SmallBox;
use yii\helpers\Html;

$this->title = 'Dashboard';

?>

<div class="site-index">
    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?= SmallBox::widget([
                'title' => '150',
                'text' => 'New Orders',
                'icon' => 'fas fa-shopping-cart',
                'theme' => 'info'
            ]) ?>
        </div>
        <div class="col-lg-4">
            <?= SmallBox::widget([
                'title' => '53%',
                'text' => 'Bounce Rate',
                'icon' => 'fas fa-chart-bar',
                'theme' => 'success'
            ]) ?>
        </div>
        <div class="col-lg-4">
            <?= SmallBox::widget([
                'title' => '44',
                'text' => 'User Registrations',
                'icon' => 'fas fa-user-plus',
                'theme' => 'warning'
            ]) ?>
        </div>
    </div>
</div>

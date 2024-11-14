<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dashboard';
?>

<div class="site-index">
    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="small-box">
                <div class="inner">
                    <h3>Eventos</h3>
                    <p>Numero de Eventos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="<?= Url::to(['evento/index']) ?>" class="small-box-footer">
                    Ver Eventos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

</div>

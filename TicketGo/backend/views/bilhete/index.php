<?php

namespace backend\controllers;

use yii\grid\GridView;
use common\models\Bilhete;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bilhetes';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bilhete-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Criar Bilhetes em Massa',['bilhete/create', 'evento_id' =>$evento_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'evento.titulo',
            'zona.lugar',
            'precounitario',
            'vendido:boolean',
            'data',
            'codigobilhete',
        ],
    ]); ?>
</div>

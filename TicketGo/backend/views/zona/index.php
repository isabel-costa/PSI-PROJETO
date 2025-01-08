<?php

use common\models\Zona;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = "Zonas de " . Html::encode($local->nome) ;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zona-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Zona', ['create' , 'local_id'=> $local_id], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'lugar',
            'porta',
            'quantidadedisponivel',

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Zona $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

<?php

use common\models\Local;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Locals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="local-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Local', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'nome',
            'morada',
            'cidade',
            'capacidade',
            [
                'class' => ActionColumn::className(),
                'template' => '{update} {delete} {zonas}',
                'buttons' => [
                    'zonas' => function ($url, $model, $key) {
                        return Html::a('Gerir Zonas', ['zona/index', 'local_id' => $model->id], [
                            'class' => 'btn btn-primary btn-sm',
                            'title' => 'Gerir zonas deste local'
                        ]);
                    },
                ],
                'urlCreator' => function ($action, Local $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>


</div>

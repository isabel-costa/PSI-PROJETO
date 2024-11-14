<?php

use common\models\Evento;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Eventos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="evento-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Evento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'titulo',
            'descricao:ntext',
            'datainicio',
            'datafim',
            'local_id',
            'categoria_id',
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'urlCreator' => function ($action, Evento $model) {
                    return Url::to(['evento/' . $action, 'id' => $model->id]);
                },
            ],
        ],
    ]) ?>


</div>

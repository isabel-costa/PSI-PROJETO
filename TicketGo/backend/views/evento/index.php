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

            [
                'attribute' => 'local_id',
                'label' => 'Local',
                'value' => function($model) {
                    return $model->local ? $model->local->nome : '(não definido)';
                },
            ],
            [
                'attribute' => 'categoria_id',
                'label' => 'Categoria',
                'value' => function($model) {
                    return $model->categoria ? $model->categoria->nome : '(não definido)';
                },
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete} {bilhete}',
                'buttons' => [
                    'bilhete' => function ($url, $model, $key) {
                        return Html::a('Criar Bilhetes', ['bilhete/create', 'evento_id' => $model->id], [
                            'class' => 'btn btn-primary btn-sm',
                            'title' => 'Criar Bilhetes'
                        ]);
                    },
                ],
                'urlCreator' => function ($action, Evento $model) {
                    return Url::to(['evento/' . $action, 'id' => $model->id]);
                },
            ],
        ],
    ]) ?>


</div>

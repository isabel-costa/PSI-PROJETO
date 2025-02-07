<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Zona $model */

$this->title = 'Create Zona';
$this->params['breadcrumbs'][] = ['label' => 'Zonas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zona-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'local' => $local
    ]) ?>

</div>

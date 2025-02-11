<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\MetodoPagamento $model */

$this->title = 'Create Metodo Pagamento';
$this->params['breadcrumbs'][] = ['label' => 'Metodo Pagamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodo-pagamento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

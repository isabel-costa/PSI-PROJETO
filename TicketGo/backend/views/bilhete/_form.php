<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $evento common\models\Evento */
/* @var $zonas common\models\Zona[] */
/* @var $bilhete common\models\Bilhete */

?>

<div class="bilhete-form">

    <h2>Criação de Bilhetes para o Evento: <?= Html::encode($evento->titulo) ?></h2>

    <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['bilhete/create', 'evento_id' => $evento->id],
    ]); ?>

    <!-- Campo para selecionar a zona -->
    <?= $form->field($bilhete, 'zona_id')->dropDownList(
        ArrayHelper::map($zonas, 'id', 'lugar'),
        ['prompt' => 'Selecione uma Zona']
    ) ?>

    <!-- Campo para preço unitário -->
    <?= $form->field($bilhete, 'precounitario')->textInput(['type' => 'number', 'step' => '0.01']) ?>

        <?= Html::submitButton('Criar Bilhetes', ['class' => 'btn btn-success']) ?>


    <?php ActiveForm::end(); ?>

</div>

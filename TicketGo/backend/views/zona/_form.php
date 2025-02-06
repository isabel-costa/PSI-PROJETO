<?php

use common\models\Evento;
use common\models\Local;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Zona $model */
/** @var yii\widgets\ActiveForm $form */
$this->title = "Zonas de " . Html::encode($local->nome) ;

?>

<div class="zona-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lugar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'porta')->textInput() ?>

    <?= $form->field($model, 'quantidadedisponivel')->textInput() ?>

    <?= $form->field($model, 'local_id')->dropDownList(
        ArrayHelper::map(Local::find()->all(), 'id', 'nome'),
        ['prompt' => 'Selecione um local']
    ) ?>

    <?= $form->field($model, 'evento_id')->dropDownList(
        ArrayHelper::map(Evento::find()->all(), 'id', 'nome'),
        ['prompt' => 'Selecione um evento']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

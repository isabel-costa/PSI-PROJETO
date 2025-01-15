<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Evento $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="evento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::label('Imagem', 'imagem') ?>
        <?= Html::fileInput('imagem', null, ['class' => 'form-control']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'datainicio')->input('datetime-local') ?>

    <?= $form->field($model, 'datafim')->input('datetime-local') ?>

    <?= $form->field($model, 'local_id')->textInput() ?>

    <?= $form->field($model, 'categoria_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Evento $model */
/** @var yii\widgets\ActiveForm $form */
/** @var common\models\Categoria[] $categorias */
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

    <?= $form->field($model, 'local_id')->dropDownList(
            \yii\helpers\ArrayHelper::map($categorias, 'id', 'nome'),
        ['prompt'=> 'Selecione um Local']
    ) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList(
            \yii\helpers\ArrayHelper::map($categorias, 'id', 'nome'),
        ['prompt' => 'Selecione uma categoria']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

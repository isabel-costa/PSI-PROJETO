<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Evento $model */
/** @var yii\widgets\ActiveForm $form */
/** @var common\models\Categoria[] $categorias */
/** @var common\models\Local[] $locais */
?>

<div class="evento-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imagem_file')->fileInput() ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'datainicio')->input('datetime-local') ?>

    <?= $form->field($model, 'datafim')->input('datetime-local') ?>

    <?= $form->field($model, 'local_id')->dropDownList(
        ArrayHelper::map($locais, 'id', 'nome'),
        ['prompt' => 'Selecione um Local']
    ) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList(
        ArrayHelper::map($categorias, 'id', 'nome'),
        ['prompt' => 'Selecione uma categoria']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

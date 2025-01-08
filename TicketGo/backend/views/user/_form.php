<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="user-create">

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?php if ($model->scenario === 'create'): ?>
            <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true]) ?>
        <?php else: ?>
            <?= $form->field($model, 'password_hash')->passwordInput(['maxlenght'=>true,   'value' => '', 'placeholder' => 'Deixe vazio para manter a senha atual']) ?>
        <?php endif; ?>

        <?= $form->field($model, 'role')->dropDownList(
            [
                'partner' => 'Partner',
                'organizer' => 'Organizer',
                'admin' => 'Admin',
            ]
        ) ?>
        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
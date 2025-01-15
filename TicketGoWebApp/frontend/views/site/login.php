<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor, preencha o seguinte formulário para fazer Login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    Esqueceu-se da password? <?= Html::a('Reponha-a aqui', ['site/request-password-reset']) ?>.
                    <br>
                    Precisa de um novo email de verificação? <?= Html::a('Resend', ['site/resend-verification-email']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login',['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <br>
                <div class="signup-link">
                    <p>Ainda não tem uma conta? <?= Html::a('Registe-se aqui', ['site/signup']) ?></p>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

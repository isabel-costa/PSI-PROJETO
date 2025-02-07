<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $evento common\models\Evento */
/* @var $zonas common\models\Zona[] */
/* @var $bilhete common\models\Bilhete */

$this->title = 'Criar Bilhetes';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<!-- Incluir o formulário de criação de bilhetes -->
<?= $this->render('_form', [
    'zonas' => $zonas,
    'bilhete' => $bilhete,
    'evento' => $evento,
    'evento_id' => $evento->id,
]) ?>

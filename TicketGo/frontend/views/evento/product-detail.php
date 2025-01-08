<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>E Shop - Bootstrap Ecommerce Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bootstrap Ecommerce Template" name="keywords">
    <meta content="Bootstrap Ecommerce Template Free Download" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="lib/slick/slick.css" rel="stylesheet">
    <link href="lib/slick/slick-theme.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<!-- Topheader -->
<div class="top-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="logo">
                    <a href="<?= Yii::getAlias('@web') ?>/">
                        <img src="../../web/img/logoticketgo.png" alt="Logo">
                    </a>
                </div>
            </div>
            <div class="col-md-6">

            </div>
            <div class="col-md-2 text-right">
                <div class="user-icons d-flex justify-content-end">
                    <div class="perfil">
                        <a href="<?= \yii\helpers\Url::to(['site/profile']) ?>">
                            <img src="../../web/img/icon_perfil.png" alt="Perfil" style="width: 40px; height: 40px;">
                        </a>
                    </div>
                    <div class="favoritos">
                        <a href="<?= \yii\helpers\Url::to(['favorito/index']) ?>">
                            <img src="../../web/img/icon_coracao.png" alt="Favoritos" style="width: 40px; height: 40px;">
                        </a>
                    </div>
                    <div class="carrinho">
                        <a href="<?= \yii\helpers\Url::to(['cart/index']) ?>">
                            <img src="../../web/img/icon_carrinho.png" alt="Carrinho" style="width: 40px; height: 40px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Topheader -->


<!-- Product Detail Start -->
<div class="product-detail">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="row align-items-center product-detail-top">
                    <div class="col-md-5">
                        <div class="product-slider-single">
                            <img src="<?= Html::encode($evento->imagemUrl) ?>" alt="<?= Html::encode($evento->titulo) ?>">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="product-content">
                            <div class="title">
                                <h2><?= Html::encode($evento->titulo) ?></h2>
                            </div>
                            <div class="col-md-7">
                                <div class="product-content">
                                    <?php
                                    $form = ActiveForm::begin([
                                        'action' => ['site/add-to-cart'], // Ação para adicionar ao carrinho
                                        'method' => 'post',
                                    ]);
                                    ?>
                                    <input type="hidden" name="evento_id" value="<?= $evento->id ?>">

                                    <?php if (!empty($zonasPrecos)): ?>
                                        <div class="price">
                                            <h3>Escolha a sua Plateia:</h3>
                                            <div class="dropdown-container">
                                                <select name="zona_id" class="custom-dropdown" required>
                                                    <option value="">Escolha uma zona...</option>
                                                    <?php foreach ($zonasPrecos as $zonaId => $detalhes): ?>
                                                        <option value="<?= $zonaId ?>">
                                                            <?= Html::encode($detalhes['lugar']) ?> - Preço: <?= is_numeric($detalhes['preco']) ? number_format($detalhes['preco'], 2) . ' €' : 'N/A' ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="quantity">
                                        <h4>Quantidade:</h4>
                                        <div class="qty">
                                            <button type="button" class="btn-minus"><i class="fa fa-minus"></i></button>
                                            <input type="number" name="quantidade" value="1" min="1" required>
                                            <button type="button" class="btn-plus"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>

                                    <div class="action">
                                        <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                            <div class="action">
                                <?php if (Yii::$app->user->isGuest): ?>
                                    <a href="#" title="É necessário fazer login para adicionar um evento aos favoritos"><i class="fa fa-heart"></i></a>
                                <?php else: ?>
                                    <!-- Verifica se o utilizador tem permissão para adicionar aos favoritos -->
                                    <?php if (Yii::$app->user->can('addToFavorites')): ?>
                                        <a href="<?= \yii\helpers\Url::to(['favorito/toggle', 'eventoId' => $evento->id]) ?>">
                                            <i class="fa fa-heart"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fa fa-heart"></i></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row product-detail-bottom">
                    <div class="tab-content">
                        <div id="description" class="container tab-pane active"><br>
                            <h4>Descrição Do Evento</h4>
                            <p>
                                <?= nl2br(Html::encode($evento->descricao)) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Product Detail End -->


<div class="featured-product">
    <div class="container">
        <div class="section-header">
            <h3>Eventos em Destaque</h3>
            <p>
                Descubra os eventos mais esperados! Garanta já o seu Bilhete.
            </p>
        </div>
        <div class="row align-items-center product-slider product-slider-4">
            <?php foreach ($eventos as $evento): ?>
                <div class="col-lg-3">
                    <div class="product-item">
                        <div class="product-image">
                            <a href="<?= Html::encode($evento->id) ?>">
                                <img src="<?= Html::encode($evento->imagemUrl) ?>" alt="<?= Html::encode($evento->titulo) ?>">
                            </a>
                            <div class="product-action">
                                <a href="#"><i class="fa fa-heart"></i></a>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="title">
                                <a href="product-detail/<?= Html::encode($evento->id) ?>"><?= Html::encode($evento->titulo) ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>



<!-- Footer Bottom Start -->
<div class="footer-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-6 copyright">
                <p>Copyright &copy; <a href="https://htmlcodex.com">HTML Codex</a>. All Rights Reserved</p>
            </div>

            <div class="col-md-6 template-by">
                <p>Template By <a href="https://htmlcodex.com">HTML Codex</a></p>
            </div>
        </div>
    </div>
</div>
<!-- Footer Bottom End -->


<!-- Back to Top -->
<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>


<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/slick/slick.min.js"></script>


<!-- Template Javascript -->
<script src="js/main.js"></script>
</body>
</html>

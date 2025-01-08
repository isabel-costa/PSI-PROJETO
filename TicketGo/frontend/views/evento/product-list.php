<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>E Shop - Lista de Eventos</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Lista de Eventos" name="keywords">
    <meta content="Eventos disponíveis para compra de ingressos" name="description">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

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

<!-- Lista de Eventos Start -->
<div class="product-list">
    <div class="container">
        <div class="section-header">
            <h3>Lista de Eventos</h3>
            <p>
                Encontre todos os eventos disponíveis e garante já o teu bilhete!
            </p>
        </div>

        <!-- Exibindo os eventos em destaque (como no slider) -->
        <div class="row align-items-center product-slider product-slider-4">
            <?php foreach ($eventos as $evento): ?>
                <div class="col-lg-3">
                    <div class="product-item">
                        <div class="product-image">
                            <a href="<?= Url::to(['product-detail', 'id' => $evento->id]) ?>">
                                <img src="<?= Html::encode($evento->imagemUrl) ?>" alt="<?= Html::encode($evento->titulo) ?>">
                            </a>
                            <div class="product-action">
                                <a href="#"><i class="fa fa-heart"></i></a>
                            </div>
                        </div>
                        <div class="product-content">
                            <div class="title">
                                <a href="<?= Url::to(['product-detail', 'id' => $evento->id]) ?>"><?= Html::encode($evento->titulo) ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Lista de Eventos End -->


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

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<!-- Custom JS -->
<script src="js/main.js"></script>

</body>
</html>

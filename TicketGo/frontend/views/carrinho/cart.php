<?php

use yii\helpers\Html;
use yii\helpers\Url;

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
                        <a href="../site/profile">
                            <img src="../../web/img/icon_perfil.png" alt="Perfil" style="width: 40px; height: 40px;">
                        </a>
                    </div>
                    <div class="favoritos">
                        <a href="<?= \yii\helpers\Url::to(['favorito/index']) ?>">

                        <a href="../site/favorites">
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


<!-- Cart Start -->
<div class="cart-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                        <tr>
                            <th>Imagem</th>
                            <th>Evento</th>
                            <th>Zona</th>
                            <th>Preço Unitario</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                            <th>Remover</th>
                        </tr>
                        </thead>
                        <tbody class="align-middle">

                        <?php $total = 0; ?>
                        <?php foreach ($linhasCarrinho as $linha): ?>
                            <?php
                            $subtotal = $linha->precounitario * $linha->quantidade;
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><img src="<?= Html::encode($linha->bilhete->evento->imagemUrl) ?>"></td>
                                <td><?= Html::encode($linha->bilhete->evento->titulo) ?></td>
                                <td><?= Html::encode($linha->bilhete->zona->lugar) ?></td>
                                <td><?= number_format($linha->precounitario, 2) ?> €</td>
                                <td><div class="qty">
                                        <form method="post" action="<?= Url::to(['carrinho/update-quantity']) ?>">
                                            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                                            <input type="hidden" name="id" value="<?= $linha->id ?>">
                                            <button type="submit" name="action" value="menos" class="btn-minus">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <input type="text" value="<?= $linha->quantidade ?>" readonly>
                                            <button type="submit" name="action" value="mais" class="btn-plus">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td><?= number_format($subtotal, 2) ?> €</td>
                                <td>
                                    <a href="<?= Url::to(['carrinho/remove-item', 'id' => $linha->id]) ?>" class="btn btn-danger btn-sm">
                                        Remover
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="cart-summary">
                    <div class="cart-content">
                        <h3>Resumo do Carrinho</h3>
                        <h4>Total do Carrinho<span><?= number_format($totalCarrinho, 2) ?> €</span></h4>
                    </div>
                    <div class="cart-btn">
                        <a href="<?= Url::to(['checkout/checkout']) ?>" class="btn btn-primary">
                            Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
<!-- Cart End -->




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

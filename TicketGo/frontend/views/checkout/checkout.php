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
    <!-- Top Header Start -->
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="logo">
                        <a href="..">
                            <img src="../../web/img/logoticketgo.png" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                </div>
                <div class="col-md-2 text-right">
                    <div class="user-icons d-flex justify-content-end">
                        <div class="perfil">
                            <a href="../site/profile">
                                <img src="../../web/img/icon_perfil.png" alt="Perfil" style="width: 40px; height: 40px;">
                            </a>
                        </div>
                        <div class="favoritos">
                            <a href="../site/favorites">
                                <img src="../../web/img/icon_coracao.png" alt="Favoritos" style="width: 40px; height: 40px;">
                            </a>
                        </div>
                        <div class="carrinho">
                            <a href="cart">
                                <img src="../../web/img/icon_carrinho.png" alt="Carrinho" style="width: 40px; height: 40px;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top Header End -->

        <!-- Checkout Start -->
    <div class="checkout">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="billing-address">
                        <h2>Dados Faturamento</h2>
                        <?php $form = ActiveForm::begin(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Nome</label>
                                <?= $form->field($profile, 'nome')->textInput(['placeholder' => 'Nome'])->label(false) ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-mail</label>
                                <?= $form->field($user, 'email')->textInput(['placeholder' => 'E-mail'])->label(false) ?>
                            </div>
                            <div class="col-md-6">
                                <label>NIF</label>
                                <?= $form->field($profile, 'nif')->textInput(['placeholder' => 'NIF'])->label(false) ?>
                            </div>
                            <div class="col-md-12">
                                <label>Morada</label>
                                <?= $form->field($profile, 'morada')->textInput(['placeholder' => 'Morada'])->label(false) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="checkout-summary">
                        <h2>Resumo Carrinho</h2>
                        <div class="checkout-content">
                            <h3>Produtos</h3>
                            <?php
                            $total = 0;
                            foreach ($linhasCarrinho as $linha): ?>
                                <?php
                                $subtotal = $linha->precounitario * $linha->quantidade;
                                $total += $subtotal;
                                ?>
                                <p><?= Html::encode($linha->bilhete->evento->titulo) ?> |
                                    <?= Html::encode($linha->bilhete->zona->lugar) ?> |
                                    Quantidade : <?= Html::encode($linha->quantidade) ?>
                                    <span><?= Html::encode($linha->precounitario) ?>€</span>
                                </p>
                            <?php endforeach; ?>
                            <h4>Total<span><?= Html::encode($total) ?>€</span></h4>
                        </div>
                    </div>

                    <div class="checkout-payment">
                        <h2>Métodos de Pagamento</h2>
                        <div class="payment-methods">
                            <div class="payment-method">
                                <?php foreach ($metodos as $metodo): ?>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input"
                                               id="payment-<?= $metodo->id ?>"
                                               name="payment_method"
                                               value="<?= $metodo->id ?>"
                                            <?= ($selectedPaymentMethod == $metodo->id) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="payment-<?= $metodo->id ?>">
                                            <?= Html::encode($metodo->nome) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="checkout-btn">
                            <?php $form = ActiveForm::begin(['action' => ['checkout/finalizar-compra'], 'method' => 'post']); ?>
                            <button type="submit" class="btn btn-primary">Finalizar Compra</button>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
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

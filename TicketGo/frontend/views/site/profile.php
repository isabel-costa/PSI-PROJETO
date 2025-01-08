<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var frontend\models\Profile $profile */
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
    <link href="../../../../../../../Users/Vasco/Desktop/site/css/style.css" rel="stylesheet">
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


<!-- My Account Start -->
<div class="my-account">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                    <a class="nav-link" id="orders-nav" data-toggle="pill" href="#orders-tab" role="tab">Orders</a>
                    <a class="nav-link" id="account-nav" data-toggle="pill" href="#account-tab" role="tab">Account Details</a>
                    <?php $form = ActiveForm::begin([
                        'action' => ['site/logout'],
                        'method' => 'post',
                    ]); ?>
                    <?= Html::submitButton('Logout', ['class' => 'nav-link']) ?>
                    <?php ActiveForm::end(); ?>                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane fade" id="orders-tab" role="tabpanel" aria-labelledby="orders-nav">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Product</th>
                                    <th>Date</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Product Name</td>
                                    <td>01 Jan 2020</td>
                                    <td>$22</td>
                                    <td>Approved</td>
                                    <td><button>View</button></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Product Name</td>
                                    <td>01 Jan 2020</td>
                                    <td>$22</td>
                                    <td>Approved</td>
                                    <td><button>View</button></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Product Name</td>
                                    <td>01 Jan 2020</td>
                                    <td>$22</td>
                                    <td>Approved</td>
                                    <td><button>View</button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="account-tab" role="tabpanel" aria-labelledby="account-nav">
                        <h4>Account Details</h4>
                        <?php $form = ActiveForm::begin(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($profile, 'nome')->textInput(['placeholder' => 'Name'])->label(false) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($profile, 'datanascimento')->textInput(['placeholder' => 'Name'])->label(false) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($profile, 'nif')->textInput(['placeholder' => 'Name'])->label(false) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($user, 'email')->textInput(['placeholder' => 'Name'])->label(false) ?>
                            </div>
                            <div class="col-md-12">
                                <?= $form->field($profile, 'morada')->textInput(['placeholder' => 'Name'])->label(false) ?>
                            </div>
                            <div class="col-md-12">
                                <button>Update Account</button>
                                <br><br>
                            </div>
                            <?php ActiveForm::end(); ?>

                        </div>
                        <h4>Password change</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="password" placeholder="Current Password">
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="New Password">
                            </div>
                            <div class="col-md-6">
                                <input type="text" placeholder="Confirm Password">
                            </div>
                            <div class="col-md-12">
                                <button>Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- My Account End -->


<!-- Footer Start -->
<div class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h1>E Shop</h1>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sollicitudin rutrum massa. Suspendisse sollicitudin rutrum massa. Vestibulum porttitor, metus sed pretium elementum, nisi nibh sodales quam, non lobortis neque felis id mauris.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="title">Useful Pages</h3>
                    <ul>
                        <li><a href="product.html">Product</a></li>
                        <li><a href="../../../../../../../Users/Vasco/Desktop/site/product-detail.html">Product Detail</a></li>
                        <li><a href="../../../../../../../Users/Vasco/Desktop/site/cart.html">Cart</a></li>
                        <li><a href="../../../../../../../Users/Vasco/Desktop/site/checkout.html">Checkout</a></li>
                        <li><a href="../../../../../../../Users/Vasco/Desktop/site/login.html">Login & Register</a></li>
                        <li><a href="my-account.html">My Account</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="title">Quick Links</h3>
                    <ul>
                        <li><a href="product.html">Product</a></li>
                        <li><a href="../../../../../../../Users/Vasco/Desktop/site/cart.html">Cart</a></li>
                        <li><a href="../../../../../../../Users/Vasco/Desktop/site/checkout.html">Checkout</a></li>
                        <li><a href="../../../../../../../Users/Vasco/Desktop/site/login.html">Login & Register</a></li>
                        <li><a href="my-account.html">My Account</a></li>
                        <li><a href="../../../../../../../Users/Vasco/Desktop/site/wishlist.html">Wishlist</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="title">Get in Touch</h3>
                    <div class="contact-info">
                        <p><i class="fa fa-map-marker"></i>123 E Shop, Los Angeles, CA, USA</p>
                        <p><i class="fa fa-envelope"></i>email@example.com</p>
                        <p><i class="fa fa-phone"></i>+123-456-7890</p>
                        <div class="social">
                            <a href=""><i class="fa fa-twitter"></i></a>
                            <a href=""><i class="fa fa-facebook"></i></a>
                            <a href=""><i class="fa fa-linkedin"></i></a>
                            <a href=""><i class="fa fa-instagram"></i></a>
                            <a href=""><i class="fa fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row payment">
            <div class="col-md-6">
                <div class="payment-method">
                    <p>We Accept:</p>
                    <img src="../../../../../../../Users/Vasco/Desktop/site/img/payment-method.png" alt="Payment Method" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="payment-security">
                    <p>Secured By:</p>
                    <img src="../../../../../../../Users/Vasco/Desktop/site/img/godaddy.svg" alt="Payment Security" />
                    <img src="../../../../../../../Users/Vasco/Desktop/site/img/norton.svg" alt="Payment Security" />
                    <img src="../../../../../../../Users/Vasco/Desktop/site/img/ssl.svg" alt="Payment Security" />
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->


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
<script src="../../../../../../../Users/Vasco/Desktop/site/js/main.js"></script>
</body>
</html>
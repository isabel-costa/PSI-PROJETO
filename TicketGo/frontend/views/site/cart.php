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
                        <a href="login">
                            <img src="../../web/img/icon_perfil.png" alt="Perfil" style="width: 40px; height: 40px;">
                        </a>
                    </div>
                    <div class="favoritos">
                        <a href="favorites">
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
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                            <th>Remover</th>
                        </tr>
                        </thead>
                        <tbody class="align-middle">
                        <?php

                        use yii\helpers\Url;

                        $subTotal = 0;
                        foreach ($carrinho as $chave => $item):
                            $evento = \app\models\Evento::findOne($item['evento_id']);
                            $zona = $evento->getZonaById($item['zona_id']); // Método para buscar detalhes da zona
                            $preco = $zona['preco'];
                            $total = $item['quantidade'] * $preco;
                            $subTotal += $total;
                            ?>
                            <tr>
                                <td>
                                    <a href="<?= Url::to(['site/product-detail', 'id' => $evento->id]) ?>">
                                        <img src="<?= Html::encode($evento->imagemUrl) ?>" alt="<?= Html::encode($evento->titulo) ?>">
                                    </a>
                                </td>
                                <td><a href="<?= Url::to(['site/product-detail', 'id' => $evento->id]) ?>"><?= Html::encode($evento->titulo) ?></a></td>
                                <td><?= Html::encode($zona['lugar']) ?></td>
                                <td><?= number_format($preco, 2) ?> €</td>
                                <td>
                                    <form method="post" action="<?= Url::to(['site/update-cart']) ?>">
                                        <div class="qty">
                                            <button type="button" class="btn-minus"><i class="fa fa-minus"></i></button>
                                            <input type="number" name="quantidade" value="<?= $item['quantidade'] ?>" min="1">
                                            <button type="button" class="btn-plus"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <input type="hidden" name="chave" value="<?= $chave ?>">
                                        <button type="submit" class="btn btn-sm btn-primary">Atualizar</button>
                                    </form>
                                </td>
                                <td><?= number_format($total, 2) ?> €</td>
                                <td>
                                    <a href="<?= Url::to(['site/remove-from-cart', 'key' => $chave]) ?>" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
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
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <div class="cart-summary">
                    <div class="cart-content">
                        <h3>Resumo do Carrinho</h3>
                        <p>Sub Total<span><?= number_format($subTotal, 2) ?> €</span></p>
                        <p>Custos de Envio<span>Grátis</span></p>
                        <h4>Total Geral<span><?= number_format($subTotal, 2) ?> €</span></h4>
                    </div>
                    <div class="cart-btn">
                        <a href="<?= Url::to(['site/checkout']) ?>" class="btn btn-primary">Finalizar Compra</a>
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


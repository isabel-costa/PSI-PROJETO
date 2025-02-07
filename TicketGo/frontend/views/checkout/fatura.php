<?php

use yii\helpers\Html;

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/fatura.css" rel="stylesheet">
    <title>Fatura de Compra</title>
</head>
<body>
<div class="header">
    <h1>Fatura de Compra</h1>
    <p><strong>Data:</strong> <?= date('d/m/Y') ?></p>
</div>

<div class="client-info">
    <h2>Dados do Cliente</h2>
    <p><strong>Nome:</strong> <?= Html::encode($profile->nome) ?></p>
    <p><strong>Email:</strong> <?= Html::encode($profile->user->email) ?></p>
    <p><strong>Morada:</strong> <?= Html::encode($profile->morada) ?></p>
    <p><strong>NIF:</strong> <?= Html::encode($profile->nif) ?></p>

</div>

<div class="payment-info">
    <h2>Método de Pagamento</h2>
    <p><strong>Selecionado</strong> <?= Html::encode($selectedPaymentMethod)?></p>
</div>

<div class="purchase-info">
    <h3>Detalhes dos Bilhetes:</h3>
    <table>
        <thead>
        <tr>
            <th>Evento</th>
            <th>Quantidade</th>
            <th>Lugar</th>
            <th>Preço Unitário</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $totalCarrinho = 0; // Inicializa o total do carrinho
        foreach ($linhasCarrinho as $linha):
            $subtotal = $linha->quantidade * $linha->bilhete->precounitario;
            $totalCarrinho += $subtotal; // Acumula o total
            ?>
            <tr>
                <td><?= Html::encode($linha->bilhete->evento->titulo) ?></td>
                <td><?= Html::encode($linha->quantidade) ?></td>
                <td><?= Html::encode($linha->bilhete->zona->lugar) ?></td>
                <td><?= Html::encode(number_format($linha->bilhete->precounitario, 2, ',', '.')) ?> €</td>
                <td><?= Html::encode(number_format($subtotal, 2, ',', '.')) ?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="total">
        <h3><strong>Total do Carrinho: <?= Html::encode(number_format($totalCarrinho, 2, ',', '.')) ?> €</strong></h3>
    </div>
</div>
</body>
</html>


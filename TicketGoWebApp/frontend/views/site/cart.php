<?php

/** @var array $carrinho */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<h1>Meu Carrinho</h1>

<?php if (empty($carrinho)): ?>
    <p>Seu carrinho está vazio.</p>
<?php else: ?>
    <table class="table">
        <thead>
        <tr>
            <th>Evento</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Total</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($carrinho as $id => $item): ?>
            <tr>
                <td><?= Html::encode($item['nome']) ?></td>
                <td>
                    <form action="<?= Url::to(['cart/updateTicketsCart', 'id' => $id]) ?>" method="post">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                        <input type="number" name="quantidade" value="<?= $item['quantidade'] ?>" min="1">
                        <button type="submit" class="btn btn-primary btn-sm">Atualizar</button>
                    </form>
                </td>
                <td><?= Yii::$app->formatter->asCurrency($item['preco']) ?></td>
                <td><?= Yii::$app->formatter->asCurrency($item['quantidade'] * $item['preco']) ?></td>
                <td>
                    <a href="<?= Url::to(['cart/removeTicketsCart', 'id' => $id]) ?>" class="btn btn-danger btn-sm">Remover</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        <strong>Total Geral: </strong> <?= Yii::$app->formatter->asCurrency(array_sum(array_map(fn($item) => $item['quantidade'] * $item['preco'], $carrinho))) ?>
    </p>

    <a href="<?= Url::to(['cart/purchaseTickets']) ?>" class="btn btn-success">Finalizar Compra</a>
<?php endif; ?>

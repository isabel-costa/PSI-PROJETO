<?php

namespace app\models;

use Yii;

class Carrinho
{
    // Retorna os itens do carrinho (simulação com sessão)
    public static function getItens()
    {
        return Yii::$app->session->get('carrinho', []);
    }

    // Atualiza a quantidade de um item no carrinho
    public static function updateTicketsCart($id, $quantidade)
    {
        $carrinho = self::getItens();
        if (isset($carrinho[$id])) {
            $carrinho[$id]['quantidade'] = $quantidade;
            Yii::$app->session->set('carrinho', $carrinho);
            return true;
        }
        return false;
    }

    // Remove um item do carrinho
    public static function removeTicketsCart($id)
    {
        $carrinho = self::getItens();
        if (isset($carrinho[$id])) {
            unset($carrinho[$id]);
            Yii::$app->session->set('carrinho', $carrinho);
            return true;
        }
        return false;
    }

    // Finaliza a compra
    public static function purchaseTickets()
    {
        $carrinho = self::getItens();
        if (empty($carrinho)) {
            return false; // Carrinho vazio
        }

        foreach ($carrinho as $item) {
            // Processa a compra (ex.: salva na tabela de faturas e bilhetes)
            // Lógica simplificada para exemplo
            $fatura = new Fatura();
            $fatura->evento_id = $item['evento_id'];
            $fatura->quantidade = $item['quantidade'];
            $fatura->preco_total = $item['quantidade'] * $item['preco'];
            if (!$fatura->save()) {
                return false;
            }
        }

        // Limpa o carrinho
        Yii::$app->session->remove('carrinho');
        return true;
    }
}
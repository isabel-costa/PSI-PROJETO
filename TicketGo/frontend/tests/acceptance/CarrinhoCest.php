<?php

namespace frontend\tests\acceptance;

use frontend\tests\AcceptanceTester;
use yii\helpers\Url;

class CarrinhoCest
{
    // Teste para adicionar um evento ao carrinho
    public function checkAddToCart(AcceptanceTester $I)
    {
        // Acesse a página de detalhes do evento (supondo que o ID do evento seja 1)
        $I->amOnRoute(['site/event-detail', 'id' => 1]);

        // Verifique que estamos na página do evento correto
        $I->see('Evento XYZ'); // Troque 'Evento XYZ' pelo nome do evento

        // Selecione a quantidade (caso haja controle de quantidade)
        $I->fillField('input[name="quantidade"]', '2'); // Defina a quantidade para 2

        // Selecione uma zona de preços (se necessário)
        $I->selectOption('select[name="zona_id"]', '1'); // Defina uma zona válida (exemplo)

        // Adicione ao carrinho
        $I->click('button[type="submit"]'); // Botão "Adicionar ao Carrinho"

        // Verifique se o item foi adicionado ao carrinho
        $I->amOnRoute(['cart/index']); // Acesse a página do carrinho
        $I->see('Carrinho de Compras'); // Verifique se a página do carrinho é exibida
        $I->see('Evento XYZ'); // Verifique se o evento foi adicionado ao carrinho
        $I->see('2'); // Verifique se a quantidade do evento no carrinho é 2
    }

    // Teste para verificar o conteúdo do carrinho
    public function checkCartContent(AcceptanceTester $I)
    {
        // Acesse a página do carrinho
        $I->amOnRoute(['cart/index']);

        // Verifique se o carrinho contém o item esperado
        $I->see('Evento XYZ'); // Nome do evento no carrinho
        $I->see('2'); // Verifique se a quantidade é 2
    }

    // Teste para remover um item do carrinho
    public function checkRemoveFromCart(AcceptanceTester $I)
    {
        // Acesse a página do carrinho
        $I->amOnRoute(['cart/index']);

        // Verifique se o evento está presente no carrinho
        $I->see('Evento XYZ'); // Nome do evento no carrinho

        // Remova o item do carrinho
        $I->click('a[href*="cart/remove"]'); // Supondo que o link tenha a ação de remoção

        // Verifique se o evento foi removido
        $I->dontSee('Evento XYZ'); // O evento não deve mais aparecer no carrinho
    }

    // Teste para verificar a quantidade total no carrinho
    public function checkCartTotal(AcceptanceTester $I)
    {
        // Acesse a página do carrinho
        $I->amOnRoute(['cart/index']);

        // Verifique se o total do carrinho está correto
        $I->see('Total:'); // O texto 'Total' deve estar presente
        $I->see('$20.00'); // Exemplo de total, ajuste conforme o valor esperado
    }
}

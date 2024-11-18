<?php

namespace console\controllers;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\console\ExitCode;

class RbacController extends Controller
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    
    public function actionAssign($roleName, $userId)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);

        if ($role === null) {
            echo "Role '$roleName' não encontrada.\n";
            return ExitCode::DATAERR;
        }

        $auth->assign($role, $userId);
        echo "Role '$roleName' atribuída ao usuário de ID $userId.\n";
        return ExitCode::OK;
    }


    public function actionInit()
    {
        $auth=Yii::$app->authManager;
        $auth->removeAll();

        //Definir Permissões

        //UTILIZADORES

        //Criar Utilizadores
        $createUsers = $auth->createPermission('createUsers');
        $createUsers->description = 'Create an user';
        $auth->add($createUsers);

        //Atualizar Utilizadores
        $updateUsers = $auth->createPermission('updateUsers');
        $updateUsers->description = 'Update an user';
        $auth->add($updateUsers);

        //Apagar Utilizadores
        $deleteUsers = $auth->createPermission('deleteUsers');
        $deleteUsers->description = 'Permission to delete users';
        $auth->add($deleteUsers);

        //Logout
        $logoutPermission = $auth->createPermission('logout');
        $logoutPermission->description = 'Logout';
        $auth->add($logoutPermission);

        //Registo
        $registerPermission = $auth->createPermission('register');
        $registerPermission->description = 'Register';
        $auth->add($registerPermission);

        //Atualizar Perfil
        $updateProfile = $auth->createPermission('updateProfile');
        $updateProfile->description = 'Update user Profile Info';
        $auth->add($updateProfile);

        //EVENTOS

        //Procurar Eventos
        $searchEvents = $auth->createPermission('searchEvents');
        $searchEvents->description = 'Search Events';
        $auth->add($searchEvents);

        //Criar Eventos
        $createEvents = $auth->createPermission('createEvents');
        $createEvents->description='Create an Event';
        $auth->add($createEvents);

        //Atualizar Eventos
        $updateEvents = $auth->createPermission('updateEvents');
        $updateEvents->description='Update an Event';
        $auth->add($updateEvents);

        //Eliminar Eventos
        $deleteEvents = $auth->createPermission('deleteEvents');
        $deleteEvents->description='Delete an Event';
        $auth->add($deleteEvents);

        //Ver Relatórios
        $viewReports = $auth->createPermission('viewReports');
        $viewReports->description = 'View Reports';
        $auth->add($viewReports);

        //FAVORITOS

        //Adicionar aos Favoritos
        $addToFavorites = $auth->createPermission('addToFavorites');
        $addToFavorites->description = 'Favorite an Event';
        $auth->add($addToFavorites);

        //Remover dos Favoritos
        $removeFromFavorites = $auth->createPermission('removeFromFavorites');
        $removeFromFavorites->description = 'Remove a Favorite Event';
        $auth->add($removeFromFavorites);

        //CARRINHO

        //Adicionar Bilhetes ao Carrinho
        $addTicketsCart = $auth->createPermission('addTicketsCart');
        $addTicketsCart->description = 'Add a Ticket to the Cart';
        $auth->add($addTicketsCart);

        //Remover Bilhetes do Carrinho
        $removeTicketsCart = $auth->createPermission('removeTicketsCart');
        $removeTicketsCart->description = 'Remove a Ticket from the Cart';
        $auth->add($removeTicketsCart);

        //COMPRA

        //Comprar Bilhetes
        $purchaseTickets = $auth->createPermission('purchaseTickets');
        $purchaseTickets->description = 'Permission to purchase tickets';
        $auth->add($purchaseTickets);

        //MÉTODOS DE PAGAMENTO

        //Criar Método de Pagamento
        $createPaymentMethod = $auth->createPermission('createPaymentMethod');
        $createPaymentMethod->description = 'Create a Payment Method';
        $auth->add($createPaymentMethod);

        //Atualizar Método de Pgamento
        $updatePaymentMethod = $auth->createPermission('updatePaymentMethod');
        $updatePaymentMethod->description = 'Update a Payment Method';
        $auth->add($updatePaymentMethod);

        //Apagar Método de Pagamento
        $deletePaymentMethod = $auth->createPermission('deletePaymentMethod');
        $deletePaymentMethod->description = 'Delete a Payment Method';
        $auth->add($deletePaymentMethod);

        //HISTÓRICO

        // View Purchase History
        $viewPurchaseHistory = $auth->createPermission('viewPurchaseHistory');
        $viewPurchaseHistory->description = 'Permission to view purchase history';
        $auth->add($viewPurchaseHistory);

        //DEFINIR ROLES E ATRIBUIR PERMISSÕES


        //Admin
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->addChild($admin, $createUsers);
        $auth->addChild($admin, $updateUsers);
        $auth->addChild($admin, $deleteUsers);
        $auth->addChild($admin, $createEvents);
        $auth->addChild($admin, $updateEvents);
        $auth->addChild($admin, $deleteEvents);
        $auth->addChild($admin, $viewReports);
        $auth->addChild($admin, $createPaymentMethod);
        $auth->addChild($admin, $updatePaymentMethod);
        $auth->addChild($admin, $deletePaymentMethod);


        //Organizador de Eventos
        $organizer = $auth->createRole('organizer');
        $auth->add($organizer);


        $auth->addChild($organizer, $createEvents);
        $auth->addChild($organizer, $updateEvents);
        $auth->addChild($organizer, $deleteEvents);

        //Parceiro Comercial
        $partner = $auth->createRole('partner');
        $auth->add($partner);


        $auth->addChild($partner, $viewReports);

        //Utilizador Registado
        $registeredUser = $auth->createRole('registeredUser');
        $auth->add($registeredUser);


        $auth->addChild($registeredUser, $updateProfile);
        $auth->addChild($registeredUser, $searchEvents);
        $auth->addChild($registeredUser, $addTicketsCart);
        $auth->addChild($registeredUser, $removeTicketsCart);
        $auth->addChild($registeredUser, $purchaseTickets);
        $auth->addChild($registeredUser, $viewPurchaseHistory);
        $auth->addChild($registeredUser, $addToFavorites);
        $auth->addChild($registeredUser, $removeFromFavorites);

        //Visitante
       /* $guest = $auth->createRole('guest');
        $auth->add($guest);

        $auth->addChild($guest, $searchEvents);
        $auth->addChild($guest, $registerPermission);*/

        //Atribuir IDs aos Roles
        $auth->assign($admin, 1);
        $auth->assign($organizer, 2);
        $auth->assign($registeredUser, 3);
        $auth->assign($partner, 4);
    }
}
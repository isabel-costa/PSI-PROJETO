<?php
namespace backend\modules\api\components;

use Yii;
use common\models\User;
use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;
use yii\web\ForbiddenHttpException;

class QueryParamAuth extends AuthMethod
{
    // Método para autenticar o utilizador
    public function authenticate($user, $request, $response)
    {
        // Obtém o token de autenticação a partir dos parâmetros da query
        $authToken = $request->getQueryParam('token');
        
        // Verifica se o token de autenticação não está vazio
        if (!empty($authToken)) {
            // Encontra a identidade do utilizador pelo token de acesso
            $identity = User::findIdentityByAccessToken($authToken);
            
            // Se a identidade for encontrada, retorna-a
            if ($identity) {
                return $identity;
            }
            // Lança uma exceção se a autenticação falhar
            throw new ForbiddenHttpException('No authentication');
        }
        // Lança uma exceção se o token não for fornecido
        throw new ForbiddenHttpException('Sem Token');
    }
}
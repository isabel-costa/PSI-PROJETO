<?php

namespace backend\modules\api\controllers;

use Yii;
use backend\modules\api\components\QueryParamAuth;
use common\models\Profile;
use common\models\User;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    public $enableCsrfValidation = false;

    // método para verificar o acesso às ações
    public function checkAccess($action, $model = null, $params = [])
    {
        // bloqueia qualquer método que não seja GET
        if (in_array($action, ['create', 'update', 'delete'])) {
            throw new ForbiddenHttpException('Erro: Você não tem permissão para realizar esta ação.');
        }
    }

    // método de login
    public function actionLogin()
    {
        $request = Yii::$app->request; // recebe os dados via query parameter
        $username = $request->getBodyParam('username');
        $password = $request->getBodyParam('password');

        // verifica se o username e a password foram fornecidos
        if (empty($username) || empty($password)) {
            throw new BadRequestHttpException('Erro: Parâmetros obrigatórios ausentes.');
        }

        // encontra o user na base de dados
        $user = User::findOne(['username' => $username]);
        
        // verifica se o user existe e se a password é válida
        if (!$user || !$user->validatePassword($password)) {
            throw new UnauthorizedHttpException('Erro: Credenciais inválidas.');
        }

        // gera a auth key caso não exista
        if (empty($user->auth_key)) {
            $user->generateAuthKey(); // método que gera a auth key
            if (!$user->save()) {
                throw new UnauthorizedHttpException('Erro ao gerar chave de autenticação.');
            }
        }

        // retorna a auth key
        return ['auth_key' => $user->auth_key, 'id'=> $user->id];
    }

    // método de signup
    public function actionSignup()
    {
        $request = Yii::$app->request;

        $username = $request->getBodyParam('username');
        $email = $request->getBodyParam('email');
        $password = $request->getBodyParam('password');
        $nome = $request->getBodyParam('nome');
        $datanascimento = $request->getBodyParam('datanascimento');
        $nif = $request->getBodyParam('nif');
        $morada = $request->getBodyParam('morada');

        // verifica se todos os parâmetros obrigatórios foram passados
        if (!$username || !$email || !$password || !$nome || !$datanascimento || !$nif || !$morada) {
            throw new BadRequestHttpException('Erro: Todos os campos são obrigatórios.');
        }

        // verifica se o username ou email já existam na base de dados
        if (User::findOne(['username' => $username]) || User::findOne(['email' => $email])) {
            throw new BadRequestHttpException('Erro: Nome de utilizador ou e-mail já se encontram registados.');
        }

        // cria um novo user
        $user = new User();

        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();                   // método que gera o auth_key
        $user->generatePasswordResetToken();        // método que gera o password_reset_token
        $user->generateEmailVerificationToken();    // método que gera o verification_token
        $user->created_at = time();                 // define o created_at para a hora atual
        $user->updated_at = time();                 // define o updated_at para a hora atual

        if ($user->save()) {
            // cria um profile associado ao user
            $profile = new Profile();
            
            $profile->user_id = $user->id;
            $profile->nome = $nome;
            $profile->datanascimento = $datanascimento;
            $profile->nif = $nif;
            $profile->morada = $morada;
            $profile->dataregisto = date('Y-m-d H:i:s');    // define a dataregisto para a hora e dia atual

            if (!$profile->save()) {
                // se o profile não for guardado, apaga o respetivo user criado
                $user->delete();
                throw new BadRequestHttpException('Erro ao criar perfil de utilizador.');
            }

            return ['auth_key' => $user->auth_key];
        } 
        
        throw new BadRequestHttpException('Erro ao criar o utilizador.');
    }
}

<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Profile; // Certifique-se de importar o modelo Profile

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public $nome; // Nome na tabela profiles
    public $datanascimento; // Data de nascimento na tabela profiles
    public $nif; // NIF na tabela profiles
    public $morada; // Morada na tabela profiles

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            // Adicione as regras de validação para os campos do perfil
            ['nome', 'required'],
            ['datanascimento', 'required'],
            ['nif', 'required'],
            ['morada', 'required'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        // Criar o usuário
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        // Salvar o usuário
        if ($user->save(false)) {
            // Criar e salvar o perfil
            $profile = new Profile(); // Supondo que você tenha o modelo Profile
            $profile->user_id = $user->id; // Chave estrangeira para a tabela user
            $profile->nome = $this->nome; // Atribuindo o valor do nome do formulário
            $profile->datanascimento = $this->datanascimento; // Atribuindo o valor da data de nascimento
            $profile->nif = $this->nif; // Atribuindo o valor do NIF
            $profile->morada = $this->morada; // Atribuindo o valor da morada

            if ($profile->save(false)) {
                // Atribuir o papel de 'registeredUser'
                $auth = \Yii::$app->authManager;
                $role = $auth->getRole('registeredUser');

                if ($role) {
                    $auth->assign($role, $user->id);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}

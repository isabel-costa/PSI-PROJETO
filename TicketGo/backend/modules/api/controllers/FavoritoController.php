<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\QueryParamAuth;
use common\models\Evento;
use common\models\Favorito;
use common\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class FavoritoController extends ActiveController
{
    public $modelClass = 'common\models\Favorito';


    // configura os comportamentos do controlador
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // adiciona autenticação via Query Params
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];
        
        return $behaviors;
    }
    

    // método para validar a autenticação do utilizador enviado nas Query Params
    public function verifyCredentials($token, $profile_id)
    {
        // procura o utilizador enviado através das Query Params na tabela User
        $user = User::find()->where(['id' => $profile_id])->andWhere(['auth_key' => $token])->one();
        
        // caso o token e o profile_id não coincidam
        if (!$user) {
            throw new UnauthorizedHttpException('Token ou ID inválidos.');
        }
    }


    // método para obter os favoritos do utilizador enviado nas Query Params
    public function actionGetFavorito()
    {
        // obtém o token e o profile_id
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);

        // busca os favoritos associados ao profile_id
        $favoritos = Favorito::find()->where(['profile_id' => $profile_id])->all();
        
        if (empty($favoritos)) {
            throw new NotFoundHttpException('Nenhum favorito foi encontrado para este utilizador.');
        }

        $favoritosLista = [];

        foreach ($favoritos as $linha) {
            // obtém o evento associado ao favorito
            $evento = Evento::findOne($linha->evento_id);

            // verifica se o evento existe antes de tentar acessá-lo
            if ($evento) {
                $favoritosLista[] = [
                    'id' => $linha->id,
                    'evento' => [
                        'evento_id' => $evento->id,
                        'titulo' => $evento->titulo,
                        'descricao' => $evento->descricao,
                        'data_inicio' => $evento->datainicio,
                        'data_fim' => $evento->datafim,
                    ]
                ];
            }
        }

        // mostra todos os favoritos do profile_id com detalhes do evento
        return [
            'message' => "Favoritos do utilizador {$profile_id}:",
            'favoritos' => $favoritosLista,
        ];
    }


    // método para adicionar um evento aos favoritos
    public function actionAddFavorito($evento_id)
    {
        // obtém o token e o profile_id
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);

        // verifica se o evento existe
        $evento = Evento::findOne($evento_id);
        if (!$evento) {
            throw new NotFoundHttpException('Evento não encontrado.');
        }

        // verifica se o favorito já existe
        $favoritoExistente = Favorito::findOne(['profile_id' => $profile_id, 'evento_id' => $evento_id]);
        if ($favoritoExistente) {
            return [
                'message' => "Este evento já se encontra nos favoritos do utilizador {$profile_id}.",
            ];
        }

        // adiciona o evento aos favoritos
        $favorito = new Favorito();

        $favorito->profile_id = $profile_id;
        $favorito->evento_id = $evento_id;

        if ($favorito->save()) {
            return [
                'message' => "Evento adicionado com sucesso aos favoritos do utilizador {$profile_id}.",
            ];
        } else {
            throw new BadRequestHttpException('Erro ao adicionar o evento aos favoritos.');
        }

        // Adiciona aos favoritos
        $novoFavorito = new Favorito();
        $novoFavorito->user_id = $userId;
        $novoFavorito->profile_id = $profile_id;

        if ($novoFavorito->save()) {
            return ['message' => 'Adicionado aos favoritos'];
        }

        return ['message' => 'Erro ao adicionar aos favoritos'];
    }


    // método para atualizar um favorito (caso haja necessidade de mudar um favorito de utilizador)
    public function actionUpdateFavorito()
    {
        // Obtém o token e o profile_id
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // Verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);

        // Busca o favorito existente
        $favorito = Favorito::findOne(['profile_id' => $profile_id, 'evento_id' => $evento_id]);

        if (!$favorito) {
            throw new NotFoundHttpException('Favorito não encontrado.');
        }

        return [
            'message' => 'O favorito já existe e não precisa ser atualizado.',
        ];
    }


    // método para eliminar um evento dos favoritos
    public function actionDeleteFavorito() 
    {
        // obtém o token e o profile_id
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);

        // busca o favorito associado ao evento
        $favorito = Favorito::findOne(['profile_id' => $profile_id, 'evento_id' => $evento_id]);

        // verifica se o favorito foi encontrado
        if (!$favorito) {
            throw new NotFoundHttpException('Evento favorito não encontrado.');
        }

        if ($favorito->delete()) {
            return [
                'message' => "Evento eliminado com sucesso da lista de favoritos do utilizador {$profile_id}.",
            ];
        } else {
            throw new BadRequestHttpException('Erro ao eliminar o evento da lista de favoritos.');
        }
    }
}

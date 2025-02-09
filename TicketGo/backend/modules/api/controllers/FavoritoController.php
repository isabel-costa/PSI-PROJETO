<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use backend\modules\api\components\QueryParamAuth;
use common\models\Evento;
use common\models\Favorito;

class FavoritoController extends ActiveController
{
    public $modelClass = 'common\models\Favorito';

    // configura os comportamentos do controlador
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // adiciona autenticação via query parameter
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];
        
        return $behaviors;
    }
    
    // Método para obter os favoritos de um perfil específico
    public function actionGetProfile($profile_id)
    {
        // Encontra todos os favoritos associados ao perfil
        $favoritos = $this->modelClass::find()->where(['profile_id' => $profile_id])->all();

        // Verifica se existem favoritos para o perfil
        if ($favoritos) {
            return $favoritos;
        } else {
            return [
                'success' => false,
                'message' => 'Este utilizador não tem eventos favoritos.',
            ];
        }
    }

    // Método para eliminar um evento dos favoritos
    public function actionDeleteFav($evento_id) {
        // Encontra o favorito associado ao evento
        $favorito = $this->modelClass::findOne(['evento_id' => $evento_id]);

        // Verifica se o favorito foi encontrado
        if ($favorito) {
            if ($favorito->delete()) {
                return [
                    'success' => true,
                    'message' => 'Evento eliminado com sucesso da lista de favoritos.',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erro ao eliminar o evento da lista de favoritos.',
                ];
            }
        } else {
            throw new NotFoundHttpException('Evento favorito não encontrado.');
        }
    }

    // Método para adicionar um evento aos favoritos
    public function actionAddFav($profile_id, $evento_id)
    {
        // Verificar se o evento existe
        $evento = Evento::findOne($evento_id);
        if (!$evento) {
            throw new NotFoundHttpException('Evento não encontrado.');
        }

        // Verificar se o favorito já existe
        $favoritoExistente = Favorito::findOne(['profile_id' => $profile_id, 'evento_id' => $evento_id]);
        if ($favoritoExistente) {
            return [
                'success' => false,
                'message' => 'Este evento já está nos favoritos.',
            ];
        }

        // Adicionar o evento aos favoritos
        $favorito = new Favorito();
        $favorito->profile_id = $profile_id;
        $favorito->evento_id = $evento_id;

        if ($favorito->save()) {
            return [
                'success' => true,
                'message' => 'Evento adicionado com sucesso aos favoritos.',
            ];
        } else {
            throw new BadRequestHttpException('Erro ao adicionar o evento aos favoritos.');
        }
    }
}

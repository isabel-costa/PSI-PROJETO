<?php
namespace backend\modules\api\controllers;

use common\models\Evento;
use common\models\Favorito;
use Yii;
use backend\modules\api\components\QueryParamAuth;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use common\models\mqttPublisher;

class FavoritoController extends ActiveController {

    public $modelClass = 'common\models\Favorito';

    //Configura os comportamentos do controlador
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // Adiciona autenticação via query parameter
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $profileId = Yii::$app->request->get('profile_id'); // Captura o parâmetro GET

        if (!$profileId) {
            throw new BadRequestHttpException('O parâmetro profile_id é obrigatório.');
        }

        // Busca apenas os favoritos do perfil específico
        $favoritos = Favorito::find()->where(['profile_id' => (int)$profileId])->all();

        if (!$favoritos) {
            return [
                'success' => false,
                'message' => 'Nenhum favorito encontrado para este perfil.',
            ];
        }

        return $favoritos;
    }






    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']); // Remove a ação padrão que retorna tudo
        return $actions;
    }



    //Método para obter os favoritos de um perfil específico
    public function actionGetProfile($profile_id)
    {
        //Encontra todos os favoritos associados ao perfil
        $favoritos = $this->modelClass::find()->where(['profile_id' => $profile_id])->all();

        //Verifica se existem favoritos para o perfil
        if ($favoritos) {
            return $favoritos;
        } else {
            return [
                'success' => false,
                'message' => 'Este utilizador não tem eventos favoritos.',
            ];
        }
    }

    //Método para eliminar um evento dos favoritos
    public function actionDeleteFav($evento_id) {
        //Encontra o favorito associado ao evento
        $favorito = $this->modelClass::findOne(['evento_id' => $evento_id]);

        //Verifica se o favorito foi encontrado
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

    public function actionAddFav($profile_id)
    {
        $userId = Yii::$app->user->id; // Obtém o ID do usuário autenticado a partir do token

        if (!$userId) {
            return ['message' => 'Usuário não autenticado'];
        }

        // Verifica se o perfil existe
        $profile = Profile::findOne($profile_id);
        if (!$profile) {
            return ['message' => 'Perfil não encontrado'];
        }

        // Verifica se já existe esse favorito
        $favorito = Favorito::findOne(['user_id' => $userId, 'profile_id' => $profile_id]);

        if ($favorito) {
            return ['message' => 'Já está nos favoritos'];
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
}
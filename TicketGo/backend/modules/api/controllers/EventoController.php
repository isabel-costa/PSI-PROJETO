<?php

namespace backend\modules\api\controllers;

use common\models\Evento;
use Yii;
use backend\modules\api\components\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use common\models\mqttPublisher;
use yii\web\Response;

class EventoController extends ActiveController {
    
    public $modelClass = 'common\models\Evento';

    // Método para verificar o acesso às ações
    public function checkAccess($action, $model = null, $params = [])
    {
        // Bloqueia qualquer método que não seja GET
        if (in_array($action, ['create', 'update', 'delete'])) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action');
        }
    }

    public function actionSearch($query)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        // Verifica se a consulta não está vazia
        if (empty($query)) {
            return [
                'eventos' => [],
                'message' => 'Nenhum evento encontrado.',
            ];
        }
        // Realiza a pesquisa no banco de dados
        $eventos = Evento::find()
            ->where(['like', 'titulo', $query])
            ->orWhere(['like', 'descricao', $query])
            ->all();
        // Se não encontrar eventos
        if (empty($eventos)) {
            return [
                'eventos' => [],
                'message' => 'Nenhum evento encontrado.',
            ];
        }
        // Retorna os eventos encontrados
        return [
            'eventos' => $eventos,
            'message' => 'Eventos encontrados.',
        ];
    }
}
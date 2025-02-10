<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use backend\modules\api\components\QueryParamAuth;
use common\models\Imagem;

class ImagemController extends ActiveController
{
    public $modelClass = 'common\models\Imagem';
}

<?php

namespace frontend\controllers;

use common\models\Evento;
use common\models\Bilhete;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;


class EventoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['searchEvents', 'viewEventDetails', 'product-list', 'product-detail'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    // Ação para listar todos os eventos com paginação
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Evento::find(),
            'pagination' => [
                'pageSize' => 10, // Define o número de itens por página
            ],
        ]);

        // Renderiza a view com a lista de eventos
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
    public function actionProductList($search)
    {
        // Inicia a query para buscar os eventos
        $query = Evento::find()->with('imagem');

        // Se houver um termo de pesquisa, filtra os eventos pelo título
        if ($search) {
            $query->andWhere(['like', 'titulo', $search]);
        }

        // Executa a consulta e obtém os eventos
        $eventos = $query->all();

        // Renderiza a view product-list com os eventos encontrados
        return $this->render('product-list', [
            'eventos' => $eventos,
            'search' => $search,
        ]);
    }
    public function actionProductDetail($id)
    {
        // Obtém os eventos em destaque (limitados a 4)
        $eventos = Evento::find()
            ->with(['imagem', 'bilhetes']) // Carrega imagem e bilhetes para os eventos
            ->limit(4)
            ->all();

        // Obtém o evento específico com suas zonas e bilhetes
        $evento = Evento::find()
            ->with(['imagem', 'local.zonas.bilhetes']) // Carrega imagem, zonas e bilhetes
            ->where(['id' => $id])
            ->one();


        // Calcula os preços dos bilhetes para cada zona
        $zonasPrecos = [];
        if ($evento && !empty($evento->local->zonas)) {
            foreach ($evento->local->zonas as $zona) {
                // vai buscar os bilhetes do evento associado à zona
                $bilhete = Bilhete::find()
                    ->where(['evento_id' => $evento->id, 'zona_id' => $zona->id])
                    ->one();

                // Armazena o preço e o nome da plateia
                $zonasPrecos[$zona->id] = [
                    'preco' => $bilhete ? $bilhete->precounitario : 'N/A',
                    'lugar' => $zona->lugar,
                ];
            }
        }


        // Passa os dados para a view
        return $this->render('product-detail', [
            'evento' => $evento,  // O evento específico
            'eventos' => $eventos, // Os outros eventos em destaque
            'zonasPrecos' => $zonasPrecos, // Preços calculados por zona
        ]);
    }

}

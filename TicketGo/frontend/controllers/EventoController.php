<?php

namespace frontend\controllers;

use common\models\Evento;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;

class EventoController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['searchEvents', 'viewEventDetails'],
                        'allow' => true,
                        'roles' => ['?', 'resgisteredUser'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Evento::find(),
            'pagination' => [
                'pageSize' => 10, // Define o número de itens por página
            ],
        ]);

        // Renderiza a view index e passa os eventos para a view
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    //Exibe todos os eventos
    public function actionSearchEvents()
    {

        //Inicializa a query
        $query = Evento::find()
            ->joinWith(['local', 'categoria']) //Join com a tabela de 'local' e 'categoria'
            ->joinWith(['bilhetes']); //Obter os bilhetes (preço e disponibilidade)
        $query = Evento::find()
            ->joinWith(['local', 'categoria', 'bilhetes']); //Join com a tabela 'locais' 'categorias' e 'bilhetes'

        //Obtem os parâmetros de filtro da URL (ou da requisição GET)
        $searchTerm = Yii::$app->request->get('search', '');
        $dataInicio = Yii::$app->request->get('data', '');
        $localNome = Yii::$app->request->get('local_nome', '');
        $categoriaNome = Yii::$app->request->get('categoria_nome', '');
        $preco = Yii::$app->request->get('preco', '');
        $disponibilidade = Yii::$app->request->get('disponibilidade', '');

        //Aplica os filtros de acordo com os parâmetros recebidos
        if (!empty($searchTerm)) {
            $query->andFilterWhere(['like', 'titulo', $searchTerm]);  // Filtro para 'titulo'
        }

        if (!empty($dataInicio)) {
            // Filtra pela data do evento, assumindo que você tenha o campo datainicio no seu modelo Event
            $query->andFilterWhere(['like', 'datainicio', $dataInicio]);  // Filtro para 'data'
        }

        if (!empty($localNome)) {
            $query->andFilterWhere(['like', 'local.nome', $localNome]);  // Filtro para 'local_nome'
        }

        if (!empty($categoriaNome)) {
            $query->andFilterWhere(['like', 'categoria.nome', $categoriaNome]);  // Filtro para 'categoria_nome'
        }

        if (!empty($preco)) {
            // Filtra pela faixa de preço dos bilhetes (usando o campo preco unitário do bilhete)
            $query->andFilterWhere(['like', 'precounitario', $preco]);  // Filtro para 'preco'
        }

        if (!empty($disponibilidade)) {
            // Filtra pela quantidade disponível dos bilhetes
            $query->andFilterWhere(['>=', 'quantidadedisponivel', $disponibilidade]);  // Filtro para 'disponibilidade'
        }

        //Executa a consulta e obtém os eventos filtrados
        $events = $query->all();

        //Devolve a view com os eventos filtrados
        return $this->render('index', ['events' => $events]);
    }

    public function actionViewEventDetails($id)
    {

        $event = Evento::findOne($id);

        //Verifica se o evento foi encontrado
        if (!$event) {
            Yii::$app->session->setFlash('error', 'Evento não encontrado.');

            return $this->redirect(['index']);
        }

        return $this->render('view', ['event' => $event]);
    }
}
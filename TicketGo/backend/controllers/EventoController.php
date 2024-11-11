<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;

class EventoController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['admin', 'organizer'],
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['guest', 'registeredUser'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new Event();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    //Exibe todos os eventos
    public function actionSearchEvents() {

        //Inicializa a query
        $query = Event::find()
            ->joinWith(['local', 'categoria']) //Join com a tabela de 'local' e 'categoria'
            ->joinWith(['bilhetes']); //Obter os bilhetes (preço e disponibilidade)

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

        $event = Event::findOne($id);

        //Verifica se o evento foi encontrado
        if (!$event) {
            Yii::$app->session->setFlash('error', 'Evento não encontrado.');
            return $this->redirect(['index']);
        }

        return $this->render('view', ['event' => $event]);
    }

}
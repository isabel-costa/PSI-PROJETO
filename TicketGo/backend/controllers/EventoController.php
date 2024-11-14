<?php

namespace backend\controllers;

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

    public function actionCreateEvent()
    {
        $model = new Evento();

        if (!Yii::$app->user->can('createEvent')) {
            Yii::$app->session->setFlash('error', 'Não tem permissão para criar eventos.');

            return $this->redirect(['index']);
        }

        $model = new Evento();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Salvar o modelo no banco de dados
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Evento criado com sucesso!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao criar o evento.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdateEvent($id)
    {
        //Verifica se o utilizador tem permissão para editar eventos
        if (!Yii::$app->user->can('updateEvent')) {
            Yii::$app->session->setFlash('error', 'Não tem permissão para editar eventos.');
            return $this->redirect(['index']);
        }

        //Procura o evento pelo ID
        $model = $this->findModel($id);

        //Se o evento foi atualizado e o formulário foi enviado com sucesso faz:
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //Atualiza o evento na bd
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Evento atualizado com sucesso!');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar o evento.');
            }
        }

        //Se não houve um post ou houve falha na validação, renderiza o formulário de edição
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDeleteEvent($id)
    {
        $event = Evento::findOne($id);

        if (!$event || !Yii::$app->user->can('deleteEvent', ['eventId' => $id])) {
            throw new ForbiddenHttpException('Não tem permissão para eliminar eventos.');
        }

        $event->delete();
        return $this->redirect(['index']);
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
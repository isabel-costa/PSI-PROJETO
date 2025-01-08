<?php

namespace backend\controllers;
use common\models\Bilhete;
use Yii;
use common\models\Evento;
use common\models\Zona;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BilheteController extends Controller
{
    public function actionIndex()
    {
        $evento_id = Yii::$app->request->get('evento_id');
        if (!$evento_id) {
            throw new \yii\web\BadRequestHttpException('O parâmetro evento_id é obrigatório.');
        }
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => Bilhete::find()->where(['evento_id' => $evento_id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'evento_id' => $evento_id,
        ]);
    }

    public function actionCreate()
    {
        $bilhete = new Bilhete();


        // Obter o ID do evento a partir da requisição
        $evento_id = Yii::$app->request->get('evento_id');
        if (!$evento_id) {
            throw new \yii\web\BadRequestHttpException('O parâmetro evento_id é obrigatório.');
        }

        // Buscar o evento pelo ID
        $evento = Evento::findOne($evento_id);
        if (!$evento) {
            throw new \yii\web\NotFoundHttpException("Evento não encontrado.");
        }

        // Obter o local associado ao evento
        $local_id = $evento->local_id;
        if (!$local_id) {
            throw new \yii\web\NotFoundHttpException("Local associado ao evento não encontrado.");
        }

        // Obter as zonas relacionadas ao local

        $zonas = Zona::find()->where(['local_id' => $local_id])->all();

        // Verificar se o formulário foi submetido

        if (Yii::$app->request->isPost) {
            // Capturar os valores enviados pelo formulário
            $zona_id = Yii::$app->request->post('Bilhete')['zona_id'];
            $preco = Yii::$app->request->post('Bilhete')['precounitario'];

            // Validar a zona selecionada
            $zona = Zona::findOne($zona_id);

            if (!$zona) {
                Yii::$app->session->setFlash('error', 'Zona selecionada não encontrada.');
                return $this->redirect(['create', 'evento_id' => $evento_id]);
            }

            // Verificar a quantidade disponível na zona
            $quantidade = $zona->quantidadedisponivel;
            if ($quantidade <= 0) {
                Yii::$app->session->setFlash('error', 'Não há bilhetes disponíveis para a zona selecionada.');
                return $this->redirect(['create', 'evento_id' => $evento_id]);
            }

            // Iniciar a transação para salvar os bilhetes
            $transaction = Yii::$app->db->beginTransaction();

            try {
                // Criar os bilhetes disponíveis para a zona
                for ($i = 0; $i < $quantidade; $i++) {
                    $bilhete = new Bilhete([
                        'evento_id' => $evento_id,
                        'zona_id' => $zona_id,
                        'precounitario' => $preco,
                        'vendido' => 0, // Inicialmente, os bilhetes não estão vendidos
                        'data' => date('Y-m-d H:i:s'), // Data atual
                        'codigobilhete' => Yii::$app->security->generateRandomString(10), // Código aleatório
                    ]);

                    if (!$bilhete->save()) {
                        throw new \Exception('Erro ao salvar bilhete: ' . json_encode($bilhete->errors));
                    }
                }

                // Commit da transação ao finalizar com sucesso
                $transaction->commit();

                Yii::$app->session->setFlash('success', "Bilhetes criados com sucesso!");
                return $this->redirect(['index', 'evento_id' => $evento_id]);
            } catch (\Exception $e) {
                // Reverter a transação em caso de erro
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Ocorreu um erro ao criar os bilhetes: ' . $e->getMessage());
                return $this->redirect(['create', 'evento_id' => $evento_id]);
            }
        }


        // Renderizar a view com os dados do evento e zonas disponíveis
        return $this->render('create', [
            'evento' => $evento,
            'zonas' => $zonas, // Passar as zonas diretamente
            'bilhete' => $bilhete, // Instanciar um novo bilhete para o formulário
        ]);
    }



}
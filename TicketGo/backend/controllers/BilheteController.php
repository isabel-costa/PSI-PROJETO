<?php

namespace backend\controllers;
use common\models\Bilhete;
use Yii;
use common\models\Evento;
use common\models\Zona;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BilheteController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }
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


        // vai buscar o evento id
        $evento_id = Yii::$app->request->get('evento_id');
        if (!$evento_id) {
            throw new \yii\web\BadRequestHttpException('O parâmetro evento_id é obrigatório.');
        }

        // vai buscar o evento pelo id
        $evento = Evento::findOne($evento_id);
        if (!$evento) {
            throw new \yii\web\NotFoundHttpException("Evento não encontrado.");
        }

        // vai buscar o local associado ao evento
        $local_id = $evento->local_id;
        if (!$local_id) {
            throw new \yii\web\NotFoundHttpException("Local associado ao evento não encontrado.");
        }


        $zonas = Zona::find()->where(['local_id' => $local_id])->all();

        // verifica de o formulario foi submetidod

        if (Yii::$app->request->isPost) {
            // Capturar os valores enviados pelo formulário
            $zona_id = Yii::$app->request->post('Bilhete')['zona_id'];
            $preco = Yii::$app->request->post('Bilhete')['precounitario'];

            // valida a zona selecionada
            $zona = Zona::findOne($zona_id);

            if (!$zona) {
                Yii::$app->session->setFlash('error', 'Zona selecionada não encontrada.');
                return $this->redirect(['create', 'evento_id' => $evento_id]);
            }

            // verifica a quantidade disponível na zona
            $quantidade = $zona->quantidadedisponivel;
            if ($quantidade <= 0) {
                Yii::$app->session->setFlash('error', 'Não há bilhetes disponíveis para a zona selecionada.');
                return $this->redirect(['create', 'evento_id' => $evento_id]);
            }

            // iniciar a transação para salvar os bilhetes
            $transaction = Yii::$app->db->beginTransaction();

            try {
                // criar os bilhetes disponíveis para a zona
                for ($i = 0; $i < $quantidade; $i++) {
                    $bilhete = new Bilhete([
                        'evento_id' => $evento_id,
                        'zona_id' => $zona_id,
                        'precounitario' => $preco,
                        'vendido' => 0, // bilhetes nao vendidos
                        'data' => date('Y-m-d H:i:s'),
                        'codigobilhete' => Yii::$app->security->generateRandomString(10),
                    ]);

                    if (!$bilhete->save()) {
                        throw new \Exception('Erro ao salvar bilhete: ' . json_encode($bilhete->errors));
                    }
                }

                $transaction->commit();

                Yii::$app->session->setFlash('success', "Bilhetes criados com sucesso!");
                return $this->redirect(['index', 'evento_id' => $evento_id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Ocorreu um erro ao criar os bilhetes: ' . $e->getMessage());
                return $this->redirect(['create', 'evento_id' => $evento_id]);
            }
        }


        return $this->render('create', [
            'evento' => $evento,
            'zonas' => $zonas,
            'bilhete' => $bilhete,
        ]);
    }



}
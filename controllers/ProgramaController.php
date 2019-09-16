<?php

namespace app\controllers;

use Yii;
use app\models\Programa;
use app\models\ProgramaSearch;
use app\models\Evento;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProgramaController implements the CRUD actions for Programa model.
 */
class ProgramaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Programa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProgramaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Programa model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Programa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Programa();
        $modelevento = new Evento();
        $nombre_evento = $modelevento->find()->all();
        $droevento= ArrayHelper::map($nombre_evento,'id_evento','nombre_evento');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_programa]);
        }

        return $this->render('create', [
            'model' => $model,
            'droevento' =>$droevento,
        ]);
    }

    /**
     * Updates an existing Programa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelevento = new Evento();
        $nombre_evento = $modelevento->find()->all();
        $droevento= ArrayHelper::map($nombre_evento,'id_evento','nombre_evento');


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_programa]);
        }

        return $this->render('update', [
            'model' => $model,
            'droevento' =>$droevento,
        ]);
    }

    /**
     * Deletes an existing Programa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Programa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Programa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Programa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList($id){
        $rows = \app\models\Programa::find()->where(['id_evento' => $id])->all();

        echo "<option value =0>Seleccione un Programa</option>";
        
        if(count($rows)>0){
            foreach($rows as $row){
                echo "<option value='$row->id_programa'>$row->nombre_programa</option>";
            }
        }
        else{
            echo "<option>No existe Ningun Programa</option>";
        }
        
    }

     public function actionListado($id){
        $rows = \app\models\Programa::find()->where(['id_evento' => $id])->all();

        echo "<option value =0>Seleccione un Programa</option>";
        
        if(count($rows)>=0){
            foreach($rows as $row){
                echo "<option value='$row->id_programa'>$row->nombre_programa</option>";
            }
        }
        else{
            echo "<option>No existe Ningun Programa</option>";
        }
        
    }











    
}


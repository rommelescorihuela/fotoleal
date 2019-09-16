<?php

namespace app\controllers;

use Yii;
use app\models\Roles;
use app\models\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RolesController extends Controller
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
     * Lists all Roles models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->identity)
        {
            return $this->redirect(['site/login']);
        }
        if(Yii::$app->user->identity->id_rol==2)
        {
            Yii::$app->session->setFlash('error', "No tiene permiso para acceder a ese modulo.");
            return $this->redirect(['site/index']);
        }
        $searchModel = new RolesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Roles model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!Yii::$app->user->identity)
        {
            return $this->redirect(['site/login']);
        }
        if(Yii::$app->user->identity->id_rol==2)
        {
            Yii::$app->session->setFlash('error', "No tiene permiso para acceder a ese modulo.");
            return $this->redirect(['site/index']);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->identity)
        {
            return $this->redirect(['site/login']);
        }
        if(Yii::$app->user->identity->id_rol==2)
        {
            Yii::$app->session->setFlash('error', "No tiene permiso para acceder a ese modulo.");
            return $this->redirect(['site/index']);
        }
        $model = new Roles();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_rol]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Roles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->user->identity)
        {
            return $this->redirect(['site/login']);
        }
        if(Yii::$app->user->identity->id_rol==2)
        {
            Yii::$app->session->setFlash('error', "No tiene permiso para acceder a ese modulo.");
            return $this->redirect(['site/index']);
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_rol]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Roles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if(!Yii::$app->user->identity)
        {
            return $this->redirect(['site/login']);
        }
        if(Yii::$app->user->identity->id_rol==2)
        {
            Yii::$app->session->setFlash('error', "No tiene permiso para acceder a ese modulo.");
            return $this->redirect(['site/index']);
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Roles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Roles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Roles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

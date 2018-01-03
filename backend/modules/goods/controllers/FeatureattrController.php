<?php

namespace backend\modules\goods\controllers;

use Yii;
use common\models\Featureattr;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ArrayDataProvider;
/**
 * FeatureattrController implements the CRUD actions for Featureattr model.
 */
class FeatureattrController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    /**
     * Lists all Featureattr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = new Query;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->select('c.*,f.feature,f.options,m.name metaname')
                            ->from("{{%featureattr}} c")
                            ->leftJoin("{{%feature}} f",'f.feature=c.feature_code')
                            ->leftJoin("{{%featuremeta}} m",'m.feature_id=f.id')
                            ->groupBy('c.id')
                            ->all(),
            'sort' => [
                'attributes' => ['id', 'order_id'],
            ],
            'key'=>'id',
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Featureattr model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Featureattr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Featureattr();
        $model->loadDefaultValues();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','success'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Featureattr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','success'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Featureattr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Featureattr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Featureattr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Featureattr::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\modules\cms\controllers;

use Yii;
use common\models\Page;
use common\models\Pagemeta;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\db\Query;
/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
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
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = new Query;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->select('c.*,m.language,m.title')
                            ->from("{{%page}} c")
                            ->leftJoin("{{%pagemeta}} m",'c.id=m.page_id')
                            // ->where('m.language=:language',[':language'=>Yii::$app->language])
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
     * Displays a single Page model.
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();
        
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post())&&$model->validate()) {
            
            $data = array();
            $data['status'] = 0;
            $data = $this->saveInfo($model);
            \Yii::$app->getSession()->setFlash('message', $data['message']);

            if($data['status']==1){
                return $this->redirect(['index']);
            }else{

                return $this->render('create', [
                    'model' => $model,
                    'meta' => Yii::$app->request->post('meta'),
                ]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
                'meta' => array(),
            ]);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())&&$model->validate()) {
            
            $data = array();
            $data['status'] = 0;
            $data = $this->saveInfo($model,'update');
            \Yii::$app->getSession()->setFlash('message', $data['message']);

            if($data['status']==1){
                return $this->redirect(['index']);
            }else{

                return $this->render('create', [
                    'model' => $model,
                    'meta' => Yii::$app->request->post('meta'),
                ]);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
                'meta' => $this->findMeta($id),
            ]);
        }
    }

    // 保存信息
    public function saveInfo($model,$action='create'){
        $data = array();
        $data['status'] = 0;
            
        $transaction=Yii::$app->db->beginTransaction();

        try{
            $model->save();
            $i = 0;
            $message = '';

            if($action=='update'){

                Pagemeta::deleteAll('page_id=:page_id',[':page_id'=>$model->id]);

                
            }
            foreach (getLanguage() as $k => $v) {
                    $i++;
                    $meta = new Pagemeta();
                    
                    $meta->page_id = $model->primaryKey;
                    $meta->language = $k;
                    $meta->attributes = Yii::$app->request->post('meta')[$k];

                    $image_attr = Yii::$app->request->post('image_attr')[$k];
                    $meta->image = serialize($image_attr);
                    
                    if($meta->validate()){
                        $meta->save();
                        $data['status'] = $i;
                    }else{
                        $message .= $v['name'].':';
                        foreach ($meta->getErrors() as $key => $value) {
                            $message .= isset($value['0']) ? $value['0'] : '';
                        }
                        $message .= '</br/>';
                    }

            }
            

            if($data['status'] ==$i && $data['status']){
                
                $transaction->commit(); 
                $data['status'] = 1;
                $data['message'] = Yii::t('app','success');
            }else{
                $transaction->rollBack();
                $data['status'] = 0;
                $data['message'] = $message;
            }
                
        }
        catch(Exception $e)
        {

            $transaction->rollBack();
            $data['status'] = 0;
            $data['message'] = $e->getMessage();
        }
        return $data;
    }
    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction=Yii::$app->db->beginTransaction();

        try{
            
            $model = $this->findModel($id);//->delete()
            Pagemeta::deleteAll('page_id=:page_id',[':page_id'=>$id]);
            $model->delete();
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
            $transaction->commit();
        }
        catch(Exception $e)
        {

            $transaction->rollBack();
            \Yii::$app->getSession()->setFlash('message', $e->getMessage()); 
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findMeta($id){

        $query = new Query();
        $arr = array();

        $rs = $query->select("a.*")
            ->from("{{%pagemeta}} a")
            ->where('a.page_id=:key',[':key'=>$id])
            ->all();
        foreach ($rs as $k => $v) {
            $arr[$v['language']] = $v;
        }
        return $arr;
    }
}

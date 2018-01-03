<?php

namespace backend\modules\cms\controllers;

use Yii;
use common\models\Tag;
use common\models\Tagmeta;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Transaction;
use yii\db\Connection;
use yii\db\Query;
/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends Controller
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
     * Lists all Tag models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = new Query;
        $provider = new ArrayDataProvider([
            'allModels' => $query->select('t.*,m.language,m.name')
                            ->from("{{%tag}} t")
                            ->leftJoin("{{%tagmeta}} m",'t.id=m.tag_id')
                            // ->where('m.language=:language',[':language'=>Yii::$app->language])
                            ->groupBy('t.id')
                            ->all(),
            'sort' => [
                'attributes' => ['id', 'order_id'],
            ],
            'key'=>'id',
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        // get the posts in the current page
        $data = $provider->getModels();

       
        // $dataProvider = new ActiveDataProvider([
        //     'query' => Tag::find(),
        // ]);

        return $this->render('index', [
            'dataProvider' => $provider,
        ]);
    }

    /**
     * Displays a single Tag model.
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
     * Creates a new Tag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Tag();
       

        $model->loadDefaultValues();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $data = $this->saveInfo($model);
            \Yii::$app->getSession()->setFlash('message', $data['message']);
            if($data['status']==1){
                return $this->redirect(['index']);
            }else{

                return $this->render('create', [
                    'model' => $model,
                    'model_tag' => Yii::$app->request->post('meta'),
                ]);
            }
           
        } else {
            $data_error = $model->getErrors();
            if(!empty($data_error)){
                \Yii::$app->getSession()->setFlash('message', $model->getErrors());
            }

            return $this->render('create', [
                'model' => $model,
                'model_tag' => array(),
            ]);
        }
    }

    /**
     * Updates an existing Tag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $data = array();

            $data = $this->saveInfo($model,'update');
            \Yii::$app->getSession()->setFlash('message', $data['message']);
            
            if($data['status']==1){
                return $this->redirect(['index']);
            }else{

                return $this->render('create', [
                    'model' => $model,
                    'model_tag' => Yii::$app->request->post('meta'),
                ]);
            }
        } else {
            $data_error = $model->getErrors();
            if(!empty($data_error)){
                \Yii::$app->getSession()->setFlash('message', $model->getErrors());
            }
            return $this->render('update', [
                'model' => $model,
                'model_tag'=>$this->findTagmeta($id),
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
                Tagmeta::deleteAll('tag_id=:tag_id',[':tag_id'=>$model->id]);
            }

                foreach (getLanguage() as $k => $v) {
                    $i++;
                    $model_tag = new Tagmeta();
                    $model_tag->tag_id = $model->primaryKey;
                    $model_tag->language = $k;
                    $model_tag->name = Yii::$app->request->post('meta')[$k]['name'];
                    if($model_tag->validate()){
                        $model_tag->save();
                        $data['status'] = $i;
                    }else{
                        $message .= $v['name'].':';
                        foreach ($model_tag->getErrors() as $key => $value) {
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
     * Deletes an existing Tag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $data = array();
        $data['status'] = 0;
        $transaction=Yii::$app->db->beginTransaction();

        try{
            
            $model = $this->findModel($id);//->delete()
            Tagmeta::deleteAll('tag_id=:tag_id',[':tag_id'=>$id]);
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
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findTagmeta($id){
        $query = new Query();
        $arr = array();

        $rs = $query->select("a.*")
            ->from("{{%tagmeta}} a")
            ->where('a.tag_id=:tag_id',[':tag_id'=>$id])
            ->all();
        foreach ($rs as $k => $v) {
            $arr[$v['language']] = $v;
        }
        return $arr;
    }
}

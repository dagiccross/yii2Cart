<?php

namespace backend\controllers;

use Yii;
use backend\models\Extension;
use backend\models\Extensionmeta;
use backend\models\Language;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ArrayDataProvider;

/**
 * ExtensionController implements the CRUD actions for Extension model.
 */
class ExtensionController extends Controller
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
        ];
    }

    /**
     * Lists all Extension models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = new Query;
        $rs = $query->select('c.*,m.language,m.name')
                            ->from("{{%extension}} c")
                            ->leftJoin("{{%extensionmeta}} m",'c.id=m.ext_id');
        if(isset($_GET['type'])){

            $rs = $rs->where('c.tag=:tag',['tag'=>Yii::$app->request->get('type')])
                    ->groupBy('c.id')
                    ->all();
        }else{
            // $rs = $rs->where('m.language=:language',[':language'=>Yii::$app->language])
            $rs = $rs->groupBy('c.id')
                    ->all();
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $rs,
            'sort' => [
                'attributes' => ['id'],
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
     * Displays a single Extension model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $list['language_listData'] = Language::listData();

        $meta = null;

        if(class_exists($model->backendModel)){

            $extension = new $model->backendModel;
            $meta = $extension->getData($id);

            if(\Yii::$app->request->isPost){
                $data = $extension->doSave($id,\Yii::$app->request->post('options'),$list['language_listData']);

                \Yii::$app->getSession()->setFlash('message', $data['message']);
                return $this->redirect(['view','id'=>$id]);
            }
        }

        return $this->render('view', [
            'model' => $model,
            'meta' =>$meta,
            'list'=>$list,
        ]);
    }

    /**
     * Creates a new Extension model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Extension();
        $list['language_listData'] = Language::listData();
        $list['urlReferrer'] = \Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $list['urlReferrer'] = Yii::$app->request->post('urlReferrer');

            $data = $this->saveInfo($model,'create',$list['language_listData']);
            \Yii::$app->getSession()->setFlash('message', $data['message']);

            if($data['status']==1){
                return strpos($list['urlReferrer'],'create')==0 ? $this->redirect($list['urlReferrer']) : $this->redirect(['index','type'=>$model->tag]);
                // return $this->redirect(['index','type'=>Yii::$app->request->get('type','ext')]);
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
                'list' => $list,
            ]);
        }
    }

    /**
     * Updates an existing Extension model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $list['language_listData'] = Language::listData();
        $list['urlReferrer'] = \Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $list['urlReferrer'] = Yii::$app->request->post('urlReferrer');

            $data = $this->saveInfo($model,'update',$list['language_listData']);
            \Yii::$app->getSession()->setFlash('message', $data['message']);

            if($data['status']==1){
                return strpos($list['urlReferrer'],'update')==0 ? $this->redirect($list['urlReferrer']) : $this->redirect(['index','type'=>$model->tag]);
                // return $this->redirect(['index','type'=>Yii::$app->request->get('type','ext')]);
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
                'list' => $list,
            ]);
        }
    }

    public function saveInfo($model,$action='create',$language){
        $data = array();
        $data['status'] = 0;
        $message = '';

        $transaction=Yii::$app->db->beginTransaction();

        try{
            $model->save();
            $i = 0;

            if($action=='update'){

                Extensionmeta::deleteAll('ext_id=:ext_id',[':ext_id'=>$model->id]);

                
            }
            foreach ($language as $k => $v) {
                    $i++;
                    $meta = new Extensionmeta();
                    
                    $meta->attributes = Yii::$app->request->post('meta')[$k];
                    
                    $meta->ext_id = $model->primaryKey;
                    $meta->language = $k;

                    // $meta->name = Yii::$app->request->post('meta')[$k]['name'];
                    // if(isset(Yii::$app->request->post('meta')[$k]['goods'])){
                    //     $meta->goods = Yii::$app->request->post('meta')[$k]['goods'];
                    // }
                    
                    // $options = Yii::$app->request->post('options')[$k];
                    // $meta->options = serialize($options);

                    
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
     * Deletes an existing Extension model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {   
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $this->findModel($id)->delete();
            Extensionmeta::deleteAll('ext_id=:ext_id',[':ext_id'=>$id]);
            $transaction->commit(); 
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
        }catch(Exception $e){
            $transaction->rollBack();
            \Yii::$app->getSession()->setFlash('message', $e->getMessage());
        }
        
        return $this->redirect(['index','type'=>Yii::$app->request->get('type','ext')]);
    }

    /**
     * Finds the Extension model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Extension the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Extension::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findMeta($id){
        $query = new Query();
        $arr = array();

        $rs = $query->select("a.*")
            ->from("{{%extensionmeta}} a")
            ->where('a.ext_id=:key',[':key'=>$id])
            ->all();
        foreach ($rs as $k => $v) {
            $arr[$v['language']] = $v;
        }
        return $arr;
    }
}

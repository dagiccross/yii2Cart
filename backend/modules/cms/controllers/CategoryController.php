<?php

namespace backend\modules\cms\controllers;

use Yii;
use common\models\Category;
use common\models\Categorymeta;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ArrayDataProvider;
/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $dataProvider = new ActiveDataProvider([
        //     'query' => Category::find(),
        // ]);
        $pid = Yii::$app->request->get('id',0);
        $query = new Query;
        $rs = $query->select('c.*,m.language,m.name')
                            ->from("{{%category}} c")
                            ->leftJoin("{{%categorymeta}} m",'c.id=m.cat_id');
        if(isset($_GET['type'])){

            // $rs = $rs->where('m.language=:language',[':language'=>Yii::$app->language])
            $rs = $rs->groupBy('c.id')
                    ->all();
        }else{
            $rs = $rs->where('c.pid=:pid',[':pid'=>$pid])
                    // ->andWhere('m.language=:language',[':language'=>Yii::$app->language])
                    ->groupBy('c.id')
                    ->all();
        }
                            
        $dataProvider = new ArrayDataProvider([
            'allModels' => $rs,
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
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        $model->loadDefaultValues();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $data = $this->saveInfo($model);
            \Yii::$app->getSession()->setFlash('message', $data['message']);
            if($data['status']==1){
                return $this->redirect(['index','id'=>$model->pid,'pid'=>Yii::$app->request->get('pid',0)]);
            }else{

                return $this->render('create', [
                    'model' => $model,
                    'meta' => Yii::$app->request->post('meta'),
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'meta'=>Yii::$app->request->post('meta'),
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $data = array();
            $data['status'] = 0;
            $data = $this->saveInfo($model,'update');
            \Yii::$app->getSession()->setFlash('message', $data['message']);

            if($data['status']==1){
                return $this->redirect(['index','id'=>$model->pid,'pid'=>Yii::$app->request->get('pid',0)]);
            }else{

                return $this->render('create', [
                    'model' => $model,
                    'meta' => Yii::$app->request->post('meta'),
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'meta'=>$this->findMeta($id),
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

                Categorymeta::deleteAll('cat_id=:cat_id',[':cat_id'=>$model->id]);

                
            }
            foreach (getLanguage() as $k => $v) {
                    $i++;
                    $meta = new Categorymeta();
                    
                    $meta->cat_id = $model->primaryKey;
                    $meta->language = $k;
                    $meta->attributes = Yii::$app->request->post('meta')[$k];

                    $meta->name = Yii::$app->request->post('meta')[$k]['name'];

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
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction=Yii::$app->db->beginTransaction();

        try{
            
            $model = $this->findModel($id);//->delete()
            Categorymeta::deleteAll('cat_id=:cat_id',[':cat_id'=>$id]);
            $model->delete();
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
            $transaction->commit();
        }
        catch(Exception $e)
        {

            $transaction->rollBack();
            \Yii::$app->getSession()->setFlash('message', $e->getMessage()); 
        }

        return $this->redirect(['index','id'=>Yii::$app->request->get('pid',0)]);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findMeta($id){
        $query = new Query();
        $arr = array();

        $rs = $query->select("a.*")
            ->from("{{%categorymeta}} a")
            ->where('a.cat_id=:key',[':key'=>$id])
            ->all();
        foreach ($rs as $k => $v) {
            $arr[$v['language']] = $v;
        }
        return $arr;
    }
}

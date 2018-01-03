<?php

namespace backend\controllers;

use Yii;
use backend\models\Upfile;
use backend\models\Upload;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\db\Query;
/**
 * UpfileController implements the CRUD actions for Upfile model.
 */
class UpfileController extends Controller
{

    public function init(){
        $this->enableCsrfValidation = false;
        parent::init();
    }
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
                        'actions' => ['index', 'create', 'update','view','json','delete','upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    /**
     * Lists all Upfile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Upload();
        $dataProvider = new ActiveDataProvider([
            'query' => Upfile::find(),
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        if(Yii::$app->request->isPost){
            $type = \Yii::$app->request->post('type');

            if($type=='pdf'){
                $model->scenario = 'pdf';
                // var_dump($model->getScenario());
                // exit();
            }

            $file = UploadedFile::getInstances($model, 'file');  

            // print_r($file);exit();
            $data = array('status'=>'0','message'=>'Validation Error!');

            if ($file&&$model->validate()) {
                $path = Yii::getAlias('@webroot').'/../uploads/';
                // echo $path;exit();
                $data['message']='DIR Not Have';

                if(@file_exists($path)){
                    //$model->file->saveAs('uploads/' .mt_rand(1100,9900) .time() .$model->file->baseName. '.' . $model->file->extension);  
                    foreach ($file as $fl) {
                        $path = Yii::getAlias('@webroot').'/../';
                        $file_name = 'uploads/'.mt_rand(1100,9900) .time() .$fl->baseName. '.' . $fl->extension;
                        $model_file = new Upfile();
                        $model_file->pic = $file_name;
                        $model_file->save();
                        $fl->saveAs($path.$file_name);  
                    }
                    $data['status']=1;
                    $data['message']='Upload Successful';
                }
                  
            }
            $data_error = $model->getErrors();

            if(!empty($data_error)){
                $string = '';
                foreach ($model->getErrors() as $key => $value) {
                    # code...
                    $string .= $value['0'];
                }
                $data['message'] = $string;
            }
            Yii::$app->getSession()->setFlash('message', $data['message']);
            
            return $this->redirect(['index']);
            // $data['message'] = Yii::$app->request->post();
            // echo json_encode($data);
            Yii::$app->end();
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model'=>$model, 
        ]);
    }

    /**
     * Displays a single Upfile model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionJson(){
        $query = new Query();
        if(Yii::$app->request->get('page')){
            $page = Yii::$app->request->get('page');
            $data_id = Yii::$app->request->get('data_id','0');
            $pageSize = 20;
            $count = $query->select('count(*)')
                    ->from("{{%upfile}}")
                    ->count();
            $pageTotal = intval($count/$pageSize)+1;

            $page = $page>$pageTotal ? $pageTotal : $page;
            $page = $page<1 ? 1 : $page;

            $rs = $query->select('pic')
                    ->from("{{%upfile}}")
                    ->limit($pageSize)
                    ->offset(($page-1)*$pageSize)
                    ->all();
            $shtml = '<div class="row" id="loadimg_info" data-total="'.$pageTotal.'" data-page="'.$page.'">';
            foreach ($rs as $k => $v) {
                $shtml .= '<div class="col-xs-6 col-md-3" >
                        <a style="min-height: 110px;" class="thumbnail" data-id="'.$data_id.'" data-src="'.$v['pic'].'">
                        <img src="'.showimg($v['pic']).'" /></a></div>';
            }
            $shtml .= '</div>';
            echo $shtml;
            exit();
        }else{
            // $SITE_URL = "http://".$_SERVER['HTTP_HOST'].'/yii2/yale';
            //文件保存目录路径
            $save_path = Yii::getAlias('@webroot').'/../';
            //图片扩展名
            $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

            $rs = $query->select('id,pic')
                                ->from("{{%upfile}}")
                                ->orderBy("order_id asc,id desc")
                                ->all();
            $file_list = array();
            $i = 0;
            foreach ($rs as $k => $v) {
                $img_ext = explode(".", $v['pic']);
                $file_ext = isset($img_ext['1']) ? $img_ext['1'] : '';
                $file_ext = strtolower($file_ext);

                $imgname = explode("/", $v['pic']);
                $filename = isset($imgname['1']) ? $imgname['1'] : '';

                $file_list[$i]['is_dir'] = false;
                $file_list[$i]['has_file'] = false;
                $file_list[$i]['filesize'] = @filesize($save_path.$v['pic']);
                $file_list[$i]['dir_path'] = '';
                 
                $file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
                $file_list[$i]['filetype'] = $file_ext;
                $file_list[$i]['filename'] = $v['pic']; //文件名，包含扩展名
                $file_list[$i]['datetime'] = date('Y-m-d H:i:s', @filemtime($save_path.$v['pic'])); //文件最后修改时间

                // if(@file_exists($save_path.$v['pic'])){

                    
                    
                // }
                $i++;

            }
            // $file_list = $query->select('COUNT(*)')
            //                     ->from("{{%upfile}}");
            $result = array();
            //相对于根目录的上一级目录
            $result['moveup_dir_path'] = '../';
            //相对于根目录的当前目录
            $result['current_dir_path'] = './';
            //当前目录的URL
            $result['current_url'] = SITE_URL.'/';
            //文件数
            $result['total_count'] = $i;
            //文件列表数组
            $result['file_list'] = $file_list;

            echo json_encode($result);
            exit();
        }
        
    }

    public function actionUpload(){
        
        //定义允许上传的文件扩展名
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        //文件保存目录路径
        $save_path = Yii::getAlias('@webroot').'/../';
        //最大文件大小
        $max_size = 1000000;

        $data = ["error" =>1,"message" =>"错误信息",'url'=>showImg('upload/datu.png')];
        
        if(Yii::$app->request->isPost){
            $data['error'] = 0;

            $file = UploadedFile::getInstanceByName('imgFile'); 
            // var_dump($file);
            // echo $file->baseName;
            // exit();
            //检查文件大小
            if ($file->size > $max_size) {
                $data['error'] = 1;
                $data['message'] = \Yii::t('app','file to big');
                echo json_encode($data);
                \Yii::$app->end();
            }
            //检查目录名
            $dir_name = \Yii::$app->request->get('dir');
            $dir_name = empty($dir_name) ? 'image' : trim($dir_name);

            if (empty($ext_arr[$dir_name])) {
                // alert("目录名不正确。");
                $data['error'] = 1;
                $data['message'] = \Yii::t('app','dir name error');
                echo json_encode($data);
                \Yii::$app->end();
            }

            //检查扩展名
            if (in_array($file->extension, $ext_arr[$dir_name]) === false) {
                $data['error'] = 1;
                $data['message'] = \Yii::t('app',"upload files not allow.\n allow".implode(",", $ext_arr[$dir_name]) . "format.");
                echo json_encode($data);
                \Yii::$app->end();
                // alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
            }

            $file_name = 'uploads/'.time() .mt_rand(1100,9900) .$file->baseName. '.' . $file->extension;
            
            $model_file = new Upfile();
            $model_file->pic = $file_name;
            $model_file->save();
            $file->saveAs($save_path.$file_name);
            $data['url'] = SITE_URL.'./'.$file_name;
        }
        

        echo json_encode($data);
        \Yii::$app->end();
    }

    
    /**
     * Creates a new Upfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Upfile();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Upfile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Upfile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $path = Yii::getAlias('@webroot').'/../';
        if(!empty($model->pic))
            @unlink($path.$model->pic);
        if(!empty($model->thumb))
            @unlink($path.$model->thumb);
        $model->delete();
        Yii::$app->getSession()->setFlash('message', '操作成功');
            
        return $this->redirect(['index']);
    }

    /**
     * Finds the Upfile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Upfile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Upfile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

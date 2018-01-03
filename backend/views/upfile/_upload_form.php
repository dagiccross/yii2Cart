<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Upfile */
/* @var $form yii\widgets\ActiveForm */
if(isset($type)&&$type=='pdf'){
  $model->setScenario('pdf');
}
?>

<div class="upfile-form">

    <?php $form = ActiveForm::begin([
    				'id'=>'upload_file',
    				'options'=>['enctype'=>'multipart/form-data',
                        'enableAjaxValidation' => false, 
                                //'onsubmit'=>"return false",
                            ],

    				]); ?>
    
    <div id="queue"></div>
    <?=Html::hiddenInput('type',isset($type) ? $type : '')?>
    <?= $form->field($model, 'file[]')->fileInput(['multiple' => true]);?> 
    <!-- D&D Markup 
        <div id="drag-and-drop-zone" class="uploader">
          <div>Drag &amp; Drop Images Here</div>
          <div class="or">-or-</div>
          <div class="browser">
            <label>
              <span>Click to open the file Browser</span>
              <input type="file" name="file[]" multiple="multiple" title='Click to add Files'>
            </label>
          </div>
        </div>
        
        <div id="fileList">
        
      </div>
      <div id="debug" >
        <h2>Debug</h2>
        <div>
          <ul>
           
          </ul>
        </div>
      </div>
    -->
    <div class="form-group">
    	<?=  Html::submitButton(Yii::t('app', 'Create'), ['class'=>'btn btn-primary','name' =>'submit-button']) ?>  

    </div>
    
    <?php ActiveForm::end(); ?>

</div>
<?php

// $this->registerJsFile("@web/web/js/ajaxfileupload.js",['position'=>3]); 
// $this->registerJs(
//    '$("document").ready(function(){
        
//         function add_log(message)
//         {
//             var template = "<li>[" + new Date().getTime() + "] - " + message + "</li>";
            
//             $("#debug").find("ul").prepend(template);
//         }
          
//           function add_file(id, file)
//           {
//             var template = "" +
//               "<div class=\'file\' id=\'uploadFile" + id + "\'>" +
//                 "<div class=\'info\'>" +
//                   "#1 - <span class=\'filename\' title=\'Size: " + file.size + "bytes - Mimetype: " + file.type + "\'>" + file.name + "</span><br /><small>Status: <span class=\'status\'>Waiting</span></small>" +
//                 "</div>" +
//                 "<div class=\'bar\'>" +
//                   "<div class=\'progress\' style=\'width:0%\'></div>" +
//                 "</div>" +
//                 "<div class=\'callback\'></div>"+
//               "</div>";
              
//               $("#fileList").prepend(template);
//           }
          
//           function update_file_status(id, status, message)
//           {
//             $("#uploadFile" + id).find("span.status").html(message).addClass(status);
//           }
          
//           function update_file_callback(id,message){
//             $("#uploadFile" + id).find(".callback").html(message);
//           }
//           function update_file_progress(id, percent)
//           {
//             $("#uploadFile" + id).find("div.progress").width(percent);
//           }
          
//           // Upload Plugin itself
//           $("#drag-and-drop-zone").dmUploader({
//             url: "'. \yii\helpers\Url::to(['upfile/index']).'",
//             dataType: "json",
//             allowedTypes: "image/*",
//             type:"POST",
//             /*extFilter: "jpg;png;gif",*/
//             onInit: function(){
//               add_log("Penguin initialized :)");
//             },
//             onBeforeUpload: function(id){
//               add_log("Starting the upload of #" + id);
              
//               update_file_status(id, "uploading", "Uploading...");
//             },
//             onNewFile: function(id, file){
//               add_log("New file added to queue #" + id);
              
//               add_file(id, file);
//             },
//             onComplete: function(){
//               add_log("All pending tranfers finished");
//             },
//             onUploadProgress: function(id, percent){
//               var percentStr = percent + "%";

//               update_file_progress(id, percentStr);
//             },
//             onUploadSuccess: function(id, data){
//               add_log("Upload of file #" + id + " completed");
              
//               add_log("Server Response for file #" + id + ": " + JSON.stringify(data));
//               console.log(data);
//               update_file_callback(id,data.message);
//               update_file_status(id, "success", "Upload Complete");
              
//               update_file_progress(id, "100%");
//             },
//             onUploadError: function(id, message){
//               add_log("Failed to Upload file #" + id + ": " + message);
              
//               update_file_status(id, "error", message);
//             },
//             onFileTypeError: function(file){
//               add_log("File " + file.name + " cannot be added: must be an image");
              
//             },
//             onFileSizeError: function(file){
//               add_log("File " + file.name + " cannot be added: size excess limit");
//             },
//             /*onFileExtError: function(file){
//               $.danidemo.addLog("#demo-debug", "error", "File " + file.name + " has a Not Allowed Extension");
//             },*/
//             onFallbackMode: function(message){
//               alert("Browser not supported(do something else here!): " + message);
//             }
//         });

//         $(".btn-primary").click(function(){
//             $("#upload-file").uploadify("upload","*");
//             return false;
//         });
//     });'
// );
?>
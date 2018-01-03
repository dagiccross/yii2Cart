<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<tr>
	<td><?php echo $model->id; ?></td>
	<td><?php echo $model->username; ?></td>
	<td><?php echo $model->email; ?></td>
	<td><?php echo $model->phone; ?></td>
	<!-- <td><?php echo $model->fen; ?></td>
	<td><?php echo $model->money; ?></td>
	<td><?php echo $model->freezing; ?></td> -->
	<!-- <td><?php echo userFlat($model->power,0); ?></td> -->
	<!-- <td><?php echo $model->loginip; ?></td> -->
	<td><?php echo empty($model->addtime) ? '' :date('d-m-Y H:i',$model->addtime); ?></td>
	<!-- <td><?php echo empty($model->modifytime) ? '' :date('d-m-Y H:i',$model->modifytime); ?></td> -->
<!-- 	<td><?php echo userStatus($model->status,0); ?></td>
 -->	<td>
		<?php if(Yii::$app->user->identity->power=='admin'):?>
		<?php echo Html::a('<span class="glyphicon glyphicon-eye-open"></span>',Url::toRoute(['user/view','id'=>$model->id])); ?>
		<?php echo Html::a('<span class="glyphicon glyphicon-pencil"></span>',Url::toRoute(['user/update','id'=>$model->id])); ?>

		<?php echo Html::a('<span class="glyphicon glyphicon-trash"></span>',Url::toRoute(['user/delete','id'=>$model->id])); ?>
		<?php endif;?>
	</td>
</tr>

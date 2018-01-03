<?php

/* @var $this yii\web\View */

use yii\helpers\Html;


?>
<section class="content-main">
<div class="what-we-do container width-10" >
<?= isset($page['oneMeta']['content']) ? showContent($page['oneMeta']['content']) : ''?>

<?php echo \frontend\extensions\Evenlist::widget();?>
</div>
</section>
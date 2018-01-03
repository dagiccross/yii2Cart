<?php
use yii\bootstrap\Tabs;
?>

<?php
	$items = array();
    $i = 0;
    foreach (getLanguage() as $k => $v) {
        $items[] = ['label'=>\Yii::t('app','Extension Infomations').':'.$v['name'],
                    'content'=>$this->render('_attr_ext', [
                                'attr' => isset($attr[$k]) ? $attr[$k] : array(),
                                'language'=>$k
                            ]),
                    'active' => $i==0 ? true : false,
                    ];
        $i++;
    }
    echo Tabs::widget([
        'items' => $items,
    ]);

?>
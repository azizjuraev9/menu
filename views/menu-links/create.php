<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MenuLinks */

$this->title = 'Создать меню ссылки';
$this->params['breadcrumbs'][] = ['label' => 'Меню ссылки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-links-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'lang' => $lang,
    ]) ?>

</div>

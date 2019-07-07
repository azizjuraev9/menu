<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MenuLinksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Меню ссылки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-links-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать меню ссылки', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id',
                'options' => [
                    'width' => 110,
                ],
            ],

            'current_lang.name',
            [
                'label' => 'Languages',
                'format' => 'html',
                'value' => function($model){
                    return $model->langs;
                },
                'options' => [
                    'width' => 220,
                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'options' => [
                    'width' => 120,
                ],
            ],
        ],
    ]); ?>

</div>

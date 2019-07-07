<?php
/**
 * Created by PhpStorm.
 * User: Aziz Juraev
 * Date: 29.06.2019
 * Time: 19:29
 */
?>
<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use juraev\menu\models\Menu;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\MenuLinks */

$this->title = 'Обновить меню ссылки: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['/menu']];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="menu-links-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="menu-links-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="col-md-9 lang_block">

            <?= $form->errorSummary([$model]); ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                    $items = [];
                    foreach ($langModels as $langModel){
                        if($langModel->lang != 'uz')
                            $items[] = [
                                'label' => $langs[$langModel->lang],
                                'content' => $form->field($langModel, '['.$langModel->lang.']name')->textInput()
                            ];
                    }

                    echo Tabs::widget([
                        'items' => $items,
                    ])
                    ?>
                </div>
            </div>

            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-3 right-block">

            <?= $form->field($model, 'icon')->textInput(['maxlength' => 60]) ?>

            <?= $form->field($model, 'menu_id')->dropDownList(ArrayHelper::map(Menu::find()->all(),'id','name'),
                ['prompt'=>'- Choose a Menu -']); ?>

            <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map($links,'id','name'),
                ['prompt'=>'- Choose a Parent Link -']); ?>

            <?= $form->field($model, 'not_link')->checkbox(); ?>

            <?= $form->field($model, 'absolute')->checkbox(); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success btn-block btn-lg' : 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="clearfix"></div>


        <?php ActiveForm::end(); ?>

    </div>
    <?php
    $this->registerJs("
    $(function(){
                //alert('I`m alive!!!');
                
        $('#menulinks-menu_id').change(function(){
                //alert('I`m alive!!!');
            $.ajax({
                url: '". Url::to(['menu-links/getlinks'])."',
                data : {
                    id : $(this).val(),
                    self_id : ".Yii::$app->request->get('id').",
                }
            }).done(function(data) {
                $('#menulinks-parent_id').html(data);
                //alert('I`m alive!!!'+data);
            });
        });

        $('#menulinks-thumb').change(function(){
             $('.thumb_block .img_block').html('');
        });
    });
    ", View::POS_END, 'dep-drop');
    ?>

</div>


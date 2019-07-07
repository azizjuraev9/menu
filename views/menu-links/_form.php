<?php

use juraev\menu\models\Menu;
use juraev\menu\models\MenuLinks;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;


$links = MenuLinks::findL();
if(Yii::$app->request->get('id'))
    $links->where(['!=','t.id',Yii::$app->request->get('id')]);
$links = $links->all();



/* @var $this yii\web\View */
/* @var $model common\models\MenuLinks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-links-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-9 lang_block">

        <?= $form->errorSummary([$lang,$model]); ?>

        <?= $form->field($lang, 'name')->textInput() ?>

        <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="col-md-3 right-block">

        <?php if(empty($lang->lang)): ?>
            <?=$lang->lang?>
            <?= $form->field($lang, 'lang')->dropDownList(
                ArrayHelper::map($model->getLangList(),'abbr','title')); ?>
        <?php else: ?>
            <div class="form-group">
                <label for="">Язык</label>
                <div class="form-control" disabled><?=$lang->lang?></div>
            </div>
        <?php endif; ?>

        <?= $form->field($model, 'icon')->textInput(['maxlength' => 60]) ?>

        <?= $form->field($model, 'menu_id')->dropDownList(ArrayHelper::map(Menu::find()->all(),'id','name'),
            ['prompt'=>'- Choose a Menu -']); ?>

        <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map($links,'id','name'),
            ['prompt'=>'- Choose a Parent Link -']); ?>

        <?= $form->field($model, 'not_link')->checkbox(); ?>

        <?= $form->field($model, 'absolute')->checkbox(); ?>

<!--        --><?//=FileUploader::widget([
//            'form' => $form,
//            'model' => $model,
//            'field' => 'thumb'
//        ]);?>


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


<?php
/**
 * Created by PhpStorm.
 * User: volonter
 * Date: 05.01.2016
 * Time: 16:50
 */

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\jui\Sortable;
use yii\web\View;

?>

<style>
    .child-menu-items{
        margin: 0;
        margin-left: 15px;
        padding: 0 15px;
        border: solid 1px #222;
        border-radius: 8px;
        background-color: rgba(255,255,255,.2);
        display: block !important;
        overflow: hidden;
        width: 20px;
        height: 2px;
    }
    .child-menu-items.collapsing{
        overflow: hidden;
    }
    .child-menu-items.in, .child-menu-items.collapsing{
        margin: 15px 0;
        width: 100%;
        height: auto;
    }
    .child-menu-items.in{
        overflow: visible;
    }
    .list-group-item{
        font-size: 16px;
        cursor: pointer;
    }
</style>

<h1 class="page-header">Menu designer</h1>

<p>
    <?=Html::a('Add new menu',['create'],['class'=>'btn btn-primary'])?>
</p>

<?php $c = 0; foreach($menus as $menu): $c++;?>
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title">
                    <?=$menu['model']->name;?>
                    <?=Html::a('edit menu',['menu/update','id'=>$menu['model']->id],['class'=>'btn btn-sm btn-primary pull-right'])?>
                    <div class="clearfix"></div>
                </h2>
            </div>
            <?php
            $url = Yii::$app->getUrlManager()->createUrl('menu/order');

            ?>
            <?= Sortable::widget([
                'items' => $menu['items'],
                'options' => ['tag' => 'ul', 'class' => 'list-group'],
                'itemOptions' => ['tag' => 'li', 'class' => 'list-group-item'],
            ]); ?>
            <div class="panel-footer">
                <?=Html::a('Add new item',['menu-links/create','menu_id'=>$menu['model']->id],['class'=>'btn btn-success'])?>
            </div>
        </div>
    </div>
<?php
if($c > 1){
    echo '<div class="clearfix"></div>';
    $c = 0;
}
endforeach;


$url = Url::to(['menu/order']);
$this->registerJs("
    $(function(){
        $('ul.list-group').sortable({
            stop : function (event, ui) {
                var data = '';
                var c = 0;
                $('#'+$(this).attr('id')+'>li').each(function(object, callback){
                    if(c != 0)
                        data += ','+$(this).attr('id');
                    else
                        data += $(this).attr('id');
                    c++;
                });
                var url = '{$url}'
                $.ajax({
                    type: 'get',
                    data: {
                        ids : data
                    },
                    url: url,
                    success : function(data){
                        console.log(data);
                    }
                });
            }
        });
    });
    ", View::POS_END, 'dep-drop');


?>



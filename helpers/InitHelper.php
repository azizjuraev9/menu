<?php
/**
 * Created by PhpStorm.
 * User: volonter
 * Date: 03.05.2016
 * Time: 17:23
 */

namespace juraev\menu\helpers;

use Yii;

class InitHelper
{
    public static function help(){
        $modelPath = Yii::getAlias('@app/models');
        $models = scandir($modelPath);
        foreach($models as $model){
            if($model != '.' && $model != '..'){
                if(copy(Yii::getAlias('@app/models').'/'.$model,Yii::getAlias('@common/models').'/'.$model))
                    echo "Model created";
            }
        }
    }
}
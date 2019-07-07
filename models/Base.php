<?php
/**
 * Created by PhpStorm.
 * User: Aziz Juraev
 * Date: 25.06.2019
 * Time: 15:12
 */

namespace juraev\menu\models;


use Yii;
use yii\db\ActiveRecord;
use yii\db\ColumnSchema;
use yii\helpers\ArrayHelper;

class Base extends ActiveRecord
{

    public function hasAttribute($name, $checkVars = true, $checkBehaviors = true)
    {
        return true;
    }


    /**
     * @var ActiveRecord $lang_model
     */
    public static $lang_model;
    public static $parent_id_attr;
    public static $lang_attr;

    public static function prepare(){
        static::$lang_model = static::$lang_model ?? static::class . 'Lang';
        static::$parent_id_attr = static::$parent_id_attr ?? 'main_id';
        static::$lang_attr = static::$lang_attr ?? 'lang';
    }

    public function init()
    {
        static::prepare();
        parent::init();
    }

    /**
     * @param null $language
     * @return \yii\db\ActiveQuery
     */
    public static function findL($language = null){
        static::prepare();
        $lang_model = static::$lang_model;
        $language = $language ?? substr(Yii::$app->language,0,2);
        return static::find()
            ->select('l.*, t.*')
            ->from(static::tableName() . ' t')
            ->leftJoin($lang_model::tableName() . ' l',
                'l.'.static::$parent_id_attr.' = t.id AND l.'.static::$lang_attr.' = :language',
                [
                    ':language'=>$language ?? Yii::$app->language
                ]);
    }

    public function getT($language = null){
        return (static::$lang_model)::findOne([static::$parent_id_attr => $this->primaryKey, static::$lang_attr => $language ?? Yii::$app->language]);
    }

    public function getLangList(){
        return Yii::$app->controller->module->languages;
    }
}
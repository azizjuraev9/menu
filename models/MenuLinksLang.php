<?php

namespace juraev\menu\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "menu_links_lang".
 *
 * @property integer $id
 * @property integer $main_id
 * @property string $lang
 * @property string $name
 *
 * @property MenuLinks $main
 */
class MenuLinksLang extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_links_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang'], 'required'],
            [['main_id'], 'integer'],
            [['lang'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 85],
            [['lang','main_id'],'unique','targetAttribute'=>['lang','main_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'main_id' => 'Main ID',
            'lang' => 'Lang',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMain()
    {
        return $this->hasOne(MenuLinks::className(), ['id' => 'main_id']);
    }
}

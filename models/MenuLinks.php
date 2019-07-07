<?php

namespace juraev\menu\models;

use Yii;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;

/**
 * This is the model class for table "menu_links".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property string $link
 * @property string $icon
 * @property integer $not_link
 * @property integer $absolute
 *
 * @property Menu $menu
 * @property MenuLinksLang[] $menuLinksLangs
 */
class MenuLinks extends Base
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'link'], 'required'],
            [['menu_id','parent_id'], 'integer'],
            [['link'], 'string', 'max' => 150],
            [['thumb'], 'string', 'max' => 100],
            [['icon'], 'string', 'max' => 60],
            [['not_link','absolute'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent',
            'menu_id' => 'Menu',
            'link' => 'Link',
            'thumb' => 'Изоброжение',
            'icon' => 'Icon',
            'not_link' => 'Not Link',
            'absolute' => 'Absolute',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuLinksLangs()
    {
        return $this->hasMany(MenuLinksLang::className(), ['main_id' => 'id']);
    }

    public function depLinks($id){
        $links = MenuLinks::findL()->where(['parent_id' => $id])->all();
        $out = '';
        foreach($links as $link)
            $out.= '<option value="'.$link->id.'">'.$link->name.'</option>';

        echo $out;
    }

    public function getLangs(){
        $links = array_map(function ($item) {
            return ['label' => $item->lang, 'url' => ['menu-links/update','id'=>$this->id,'lang'=>$item->lang]];
        }, MenuLinksLang::findAll(['main_id' => $this->id]) );
        $links[] = ['label' => 'Add new', 'url' => ['menu-links/update','id'=>$this->id,'lang'=>'new']];
        $links = '<span class="dropdown"> <a href="#" data-toggle="dropdown" class="dropdown-toggle">Update <b class="caret"></b></a>'.Dropdown::widget([
              'items' => $links, ]). '</span>';
        return $links;
    }
}

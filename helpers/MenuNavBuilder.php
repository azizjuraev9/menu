<?php
/**
 * Created by PhpStorm.
 * User: Aziz Juraev
 * Date: 25.06.2019
 * Time: 12:21
 */

namespace juraev\menu\helpers;


use juraev\menu\models\Menu;
use juraev\menu\models\MenuLinks;
use Yii;

class MenuNavBuilder
{

    /**
     * @var MenuLinks[] $links
     */
    private static $links;
    private static $submenuOptions;

    public static function build($id,$submenuOptions = []){

        if(($menu = Menu::findOne($id)) == null)
            return [];

        self::$links = MenuLinks::findL()->where(['menu_id' => $id])->orderBy('sort')->all();
        self::$submenuOptions = $submenuOptions;

        $items = [];

        foreach (self::$links as $k=>$link){
            if($link->parent_id != null)
                continue;
            if($link->absolute)
                $items[$link->id] = ['label'=>$link->name ?? '','url'=>$link->link,'items'=>self::getSubLinks($link->id)];
            else
                $items[$link->id] = ['label'=>$link->name ?? '','linkOptions'=>['target'=>'_blank'],'url'=>[$link->link],'items'=>self::getSubLinks($link->id)];
            unset(self::$links[$k]);
        }

        return $items;

    }

    private static function getSubLinks($parent_id){
        $items = [];

        foreach (self::$links as $k => $link){

            if($link->parent_id != $parent_id)
                continue;

            $params = [];

            $sub_items = self::getSubLinks($link->id);

            if(isset($sub_items[0])){

                $params = self::$submenuOptions;
            }

            if($link->link == 'http://uzpassbooking.com')
                $link->link = 'http://uzpassbooking.com/home/?lang='.Yii::$app->session['language_id'] ?? 1;

            if(is_file(Yii::getAlias('@app/web').$link->link) || $link->absolute)
                $items[] = array_merge([
                    'label'=>$link->name ?? '',
                    'url'=>$link->link,
                    'linkOptions'=>['target'=>'_blank'],
                    'items'=>$sub_items
                ],$params);
            else
                $items[] = array_merge(['label'=>$link->name ?? '','url'=>[$link->link],'items'=>$sub_items],$params);

            unset(self::$links[$k]);
        }

        return $items;
    }

}
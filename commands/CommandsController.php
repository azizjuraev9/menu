<?php
/**
 * Created by PhpStorm.
 * User: volonter
 * Date: 03.05.2016
 * Time: 17:52
 */

namespace juraev\menu\commands;
require ("../migrations/m160503_063746_create_menu.php");

use yii\web\Controller;

class CommandsController extends Controller
{

    public function actionInit(){
        $migrate = new m160503_063746_create_menu;
        $migrate->safeUp();
    }

}
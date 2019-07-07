<?php

namespace juraev\menu;

use app\models\User;
use Yii;
use yii\filters\VerbFilter;

/**
 * Menu module definition class
 */
class Menu extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'juraev\menu\controllers';
    public $defaultRoute = 'menu/new';
    public $languages = [];
    public $editors = ['@'];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'juraev\menu\commands';
        }
        // custom initialization code goes here
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->editors,
                    ]
                ]
            ]
        ];
    }
}

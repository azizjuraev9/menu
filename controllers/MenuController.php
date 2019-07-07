<?php

namespace juraev\menu\controllers;

use app\widgets\new_layout\SecondaryMenu;
use Yii;
use juraev\menu\models\Menu;
use juraev\menu\models\MenuSearch;
use juraev\menu\models\MenuLinks;
use yii\bootstrap\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\jui\Sortable;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['menu/new']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['menu/new']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

//        return $this->redirect(['index']);
        return $this->redirect(['menu/new']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    // NEW BETTER MENU //////////////////////////////////////////////////////////

    public function actionNew(){
//        die(Yii::getAlias('@common/models'));
        $menu_a = [];
        $menus = Menu::find()->all();
        foreach($menus as $menu){
            $menu_a[] = [
                'model' => $menu,
                'items' => $this->itms($menu->id)
            ];
        }
        return $this->render('new',[
            'menus' => $menu_a,
        ]);
    }

    private function itms($id){
        $itms = [];
        if($models = MenuLinks::findL()->where(['menu_id'=>$id,'parent_id'=>null])->orderBy('sort')->all()){
            foreach($models as $model){
                $itms[] = [
                    'content' => $this->renderItm($model),
                    'options' => ['id' => $model->id],
                ];
            }
        }
        return $itms;
    }

    /**
     * Lists all Menu models.
     * @param $model MenuLinks
     * @return string
     */
    private function renderItm($model,$is_child = false){
        $itm = '';
        if($is_child)
            $itm = '<li class="list-group-item">';

        $itm .= '<div class="col-xs-7"  data-toggle="collapse" data-target="#child'.$model->id.'">'.$model->name.'</div>';

        $model->controller = 'menu-links';
        $itm .= '<div class="col-xs-5 text-right">';
//        $itm .= '{ '.$model->getLangs().' } | ';
        $itm .= Html::a('Update',['menu-links/update','id'=>$model->id,'lang'=>''],['data-method'=>'post']).' | ';
        $itm .= Html::a('Delete',['menu-links/delete','id'=>$model->id],['data-method'=>'post']).' | ';
        $itm .= Html::a('Add child',['menu-links/create','parent_id'=>$model->id]);
        $itm .= '</div>';

        $itm .= '<div class="clearfix"></div>';
        if($childs = MenuLinks::findL()->where(['menu_id'=>$model->menu_id,'parent_id'=>$model->id])->orderBy('sort')->all())
            $itm .= '<div class="child-menu-items collapse" id="child'.$model->id.'">'.Sortable::widget([
                    'items' => $this->modelItms($childs,false),
                    'options' => ['tag' => 'ul', 'class' => 'list-group'],
                    'itemOptions' => ['tag' => 'li', 'class' => 'list-group-item'],
                    'clientOptions' => ['cursor' => 'move'],
                ]).'</div>';



        if($is_child)
            $itm .= '</li>';

        return $itm;
    }

    private function modelItms($models,$is_child){
        $itms = [];

        foreach($models as $model){
            $itms[] = [
                'content' => $this->renderItm($model,$is_child),
                'options' => ['id' => $model->id],
            ];
        }

        return $itms;
    }

    public function actionOrder($ids){
        if($ids){
            $ids = explode(',',$ids);
            if(is_array($ids)){
                $c = 0;
                foreach($ids as $id){
                    if($model = MenuLinks::findOne($id)){
                        $c++;
                        $model->sort = $c;
                        $model->save(false);
                    }
                }
                return 'Success!!!';
            }
        }
    }


    /**
     * Finds the MenuLinks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuLinks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findItem($id)
    {
        if (($model = MenuLinks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

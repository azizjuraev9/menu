<?php
/**
 * Created by PhpStorm.
 * User: Aziz Juraev
 * Date: 25.06.2019
 * Time: 16:37
 */

namespace juraev\menu\controllers;


use Yii;
use yii\web\Controller;

class LangC extends Controller {

    protected $model_class;
    protected $search_model_class;
    protected $lang_model_class;

    protected $create_redirect;
    protected $update_redirect;
    protected $delete_redirect;

    /**
     * Lists all BlogPosts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new $this->search_model_class;
        $lang = \Yii::$app->language;
        $dataProvider = $searchModel->searchLang(Yii::$app->request->queryParams,$lang);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BlogPosts model.
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
     * Creates a new BlogPosts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    protected function create_after_model_cr($model){
        return $model;
    }

    protected function create_after_lang_model_cr($lang_model){
        return $lang_model;
    }

    public function actionCreate()
    {
        $model = new $this->model_class;
        $lang = new $this->lang_model_class;

        $model = $this->create_after_model_cr($model);
        $lang = $this->create_after_lang_model_cr($lang);

        if ($model->load(Yii::$app->request->post()) && $lang->load(Yii::$app->request->post())) {
            if($model->validate() && $lang->validate()){
                $model->save();
                $lang->main_id = $model->id;
                $lang->save();
                if(!$this->create_redirect)
                    return $this->redirect(['view', 'id' => $model->id]);
                else
                    return $this->redirect($this->create_redirect);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'lang' => $lang,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'lang' => $lang,
            ]);
        }
    }

    protected function after_update_findModel($model){
        return $model;
    }

    protected function after_update_findLangModel($model){
        return $model;
    }

    /**
     * Updates an existing BlogPosts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$lang)
    {
        if(empty($lang))
            $lang = Languages::main_lang();

        $model = $this->findModel($id);

        $model = $this->after_update_findModel($model);

        $langM = null;
        if($lang == 'new'){
            $langM = new $this->lang_model_class;
            $langM->main_id = $model->id;
        }else{
            $langM = $this->findLangModel($model->id,$lang);
        }

        $langM = $this->after_update_findLangModel($langM);


        if ($model->load(Yii::$app->request->post()) && $langM->load(Yii::$app->request->post())) {
            if($model->validate() && $langM->validate()){
                $model->save();
                $langM->save();
                if(!$this->update_redirect)
                    return $this->redirect(['view', 'id' => $model->id]);
                else
                    return $this->redirect($this->update_redirect);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'lang' => $langM,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'lang' => $langM,
            ]);
        }
    }

    protected function delete_before_delete($model){ return $model; }

    /**
     * Deletes an existing BlogPosts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->delete_before_delete($model);
        $model->delete();
        if(!$this->delete_redirect)
            return $this->redirect(['index']);
        else
            return $this->redirect($this->delete_redirect);
    }

    /**
     * Finds the BlogPosts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlogPosts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /**
         * $modelClass yii\db\ActiveRecord
         */
        $modelClass = $this->model_class;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findMixedModel($id,$lang = null){
        /**
         * $modelClass yii\db\ActiveRecord
         */
        $modelClass = $this->model_class;
        $langModelClass = $this->lang_model_class;
        if(!$lang)
            $lang = Languages::main_lang();
        $model = $modelClass::find()
            ->from($modelClass::tableName().' t')
            ->leftJoin($langModelClass::tableName().' l',[
                'l.main_id'=>$id,
                'l.lang'=>$lang
            ])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findLangModel($id,$lang)
    {
        $langModelClass = $this->lang_model_class;
        if (($model = $langModelClass::findOne(['main_id'=>$id,'lang'=>$lang])) !== null) {
            return $model;
        } else {
            return null;
        }
    }


}
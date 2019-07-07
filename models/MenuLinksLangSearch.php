<?php

namespace juraev\menu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use juraev\menu\models\MenuLinksLang;

/**
 * MenuLinksLangSearch represents the model behind the search form about `juraev\menu\models\MenuLinksLang`.
 */
class MenuLinksLangSearch extends MenuLinksLang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'main_id'], 'integer'],
            [['lang', 'name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MenuLinksLang::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'main_id' => $this->main_id,
        ]);

        $query->andFilterWhere(['like', 'lang', $this->lang])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}

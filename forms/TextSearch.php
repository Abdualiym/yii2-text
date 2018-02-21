<?php

namespace abdualiym\text\forms;

use abdualiym\text\entities\Text;
use abdualiym\text\helpers\TextHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TextSearch extends Model
{
    public $id;
    public $title;
    public $status;
    public $page;

    public function __construct($page, array $config = [])
    {
        $this->page = $page;
        parent::__construct($config);
    }


    public function rules(): array
    {
        return [
            [['id', 'status',], 'integer'],
            [['title'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Text::find();

//        echo $this->page;die;

        if ($this->page) {
            $query->andWhere(['is_article' => false]);
        } else {
            $query->andWhere(['is_article' => true]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
            'pagination' => [
                'pageParam' => 'p'    
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }

    public function statusList(): array
    {
        return TextHelper::statusList();
    }
}

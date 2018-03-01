<?php
namespace app\models;

use yii\db\ActiveQuery;

class ArticleQuery extends ActiveQuery
{
    // conditions appended by default (can be skipped)
    public function init()
    {
        // $this->andOnCondition(['deleted' => false]);
        parent::init();
    }

    // ... add customized query methods here ...

    public function active($status = true)
    {
        return $this->andOnCondition(['status' => $status]);
    }
}

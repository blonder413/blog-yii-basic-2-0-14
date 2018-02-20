<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            //'photo',
            //'status',
            [
                'attribute' => 'status',
                'format'    => 'raw',
                'value'     => function($searchModel) {
                    if ($searchModel->status === 0) {
                        return "<span class='glyphicon glyphicon-remove'></span>";
                    } else {
                        return "<span class='glyphicon glyphicon-ok'></span>";
                    }
                }
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'rowOptions'    => function($model){
            if ($model->status == User::STATUS_DELETED) {
                return ['class' => 'danger'];
            } elseif ($model->status == User::STATUS_ACTIVE) {
                return ['class' => 'success'];
            }
        },
    ]); ?>
</div>

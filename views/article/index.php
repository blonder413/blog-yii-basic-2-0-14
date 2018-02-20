<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Article'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'number',
            'title',
            //'slug',
            'topic',
            //'detail:ntext',
            //'abstract',
            //'video',
            //'type_id',
            //'download',
            //'category_id',
            [
                'attribute' => 'category_id',
                'value'     => 'category.category',
            ],
            [
                'attribute' => 'course_id',
                'value'     => 'course.course',
            ],
            //'tags',
            //'status',
            'visit_counter',
            'download_counter',
            //'course_id',
            //'created_by',
            //'created_at',
            //'updated_by',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'rowOptions'    => function($model){
            if ($model->status) {
                return ['class' => 'success'];
            } else {
                return ['class' => 'danger'];
            }
        },
    ]); ?>
</div>

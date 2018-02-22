<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use app\models\Article;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php if (Yii::$app->user->can('article-create')): ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create Article'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

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
                'format'    => 'raw',
                'filter'    => Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'category_id',
                                'data' => \yii\helpers\ArrayHelper::map(\app\models\Category::find()->all(), 'id', 'category'),
                                'options' => ['placeholder' => 'Seleccione...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
            ],
            [
                'attribute' => 'course_id',
                'value'     => 'course.course',
                'format'    => 'raw',
                'filter'    => Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'course_id',
                                'data' => \yii\helpers\ArrayHelper::map(\app\models\Course::find()->all(), 'id', 'course'),
                                'options' => ['placeholder' => 'Seleccione...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
            ],
            //'tags',
            //'status',
            'visit_counter',
            'commentsCount',
            'download_counter',
            //'course_id',
            'created_by',
            //'created_at',
            //'updated_by',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {change-status}',
                'buttons' => [
                    'change-status' => function ($url, $model, $key) {
                        if ($model->status == Article::STATUS_INACTIVE) {
                            return Html::a('<span class="glyphicon glyphicon-thumbs-up"></span>', $url,
                                [ 'title' => Yii::t('app', 'Active this article'), ]
                            );
                        } elseif ($model->status == Article::STATUS_ACTIVE) {
                            return Html::a('<span class="glyphicon glyphicon-thumbs-down"></span>', $url,
                                [ 'title' => Yii::t('app', 'Inactive this article'), ]
                            );
                        }
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url,
                            [ 'title' => Yii::t('app', 'Actualizar'), ]
                        );
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return yii\helpers\Url::to(['article/view', 'id' => $key]);
                    } elseif ($action === 'change-status') {
                        return yii\helpers\Url::to(['article/change-status', 'id' => $key]);
                    } elseif ($action == 'update') {
                        return yii\helpers\Url::to(['article/update/', 'id' => $key]);
                    } elseif ($action === 'delete') {
                        return yii\helpers\Url::to(['article/delete/', 'id' => $key]);
                    }
                }
            ],
        ],

        'rowOptions'    => function($model){
            if ($model->status == Article::STATUS_INACTIVE) {
                return ['class' => 'danger'];
            } elseif ($model->status == Article::STATUS_ACTIVE) {
                return ['class' => 'success'];
            }
        },
    ]); ?>
</div>

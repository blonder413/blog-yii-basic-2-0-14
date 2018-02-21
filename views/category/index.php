<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
      <?php
      Modal::begin([
        'header'  => '<h2>' . Yii::t('app', 'Create Category') . '</h2>',
        'toggleButton'  => ['label' => Yii::t('app', 'Create Category'), 'class' => 'btn btn-success']
      ]);

      echo $this->render('/category/create', ['model' => new Category()]);

      Modal::end();
      ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'category',
            //'slug',
            //'image',
            'description',
            'countArticles',
            [
                'attribute' => 'created_by',
                'value'     => 'createdBy.name',
            ],
            'created_at',
            [
                'attribute' => 'updated_by',
                'value'     => 'updatedBy.name',
            ],
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

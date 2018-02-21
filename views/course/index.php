<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use app\models\Course;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Courses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
      <?php
      Modal::begin([
        'header'  => '<h2>' . Yii::t('app', 'Create Course') . '</h2>',
        'toggleButton'  => ['label' => Yii::t('app', 'Create Course'), 'class' => 'btn btn-success']
      ]);

      echo $this->render('/course/create', ['model' => new Course()]);

      Modal::end();
      ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'course',
            //'slug',
            'description:ntext',
            //'image',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

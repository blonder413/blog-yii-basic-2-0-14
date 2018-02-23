<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use app\models\Type;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php if (Yii::$app->user->can('type-create')): ?>
    <p>
      <?php
      Modal::begin([
        'header'  => '<h2>' . Yii::t('app', 'Create Type') . '</h2>',
        'toggleButton'  => ['label' => Yii::t('app', 'Create Type'), 'class' => 'btn btn-success']
      ]);

      echo $this->render('/type/create', ['model' => new Type()]);

      Modal::end();
      ?>
    </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'type',
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

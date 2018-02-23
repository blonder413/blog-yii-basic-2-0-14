<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\fileinput\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <!-- https://packagist.org/packages/2amigos/yii2-file-input-widget -->
<!--
    <?=FileInput::widget([
        'model' => $model,
        'attribute' => 'file', // image is the attribute
        // using STYLE_IMAGE allows me to display an image. Cool to display previously
        // uploaded images
        //'thumbnail' => $model->getAvatarImage(),
        'style' => FileInput::STYLE_IMAGE
    ]);?>
-->
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

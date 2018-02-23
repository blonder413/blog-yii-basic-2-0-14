<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use dosamigos\fileinput\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if($model->isNewRecord): ?>
      <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?php else: ?>
      <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => true]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true, 'value' => '']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <!-- https://packagist.org/packages/2amigos/yii2-file-input-widget -->
    <!--
    <?=FileInput::widget([
        'model' => $model,
        'options' => ['accept' => 'image/jpg, image/jpeg', 'multiple' => false],
        'attribute' => 'file', // image is the attribute
        // using STYLE_IMAGE allows me to display an image. Cool to display previously
        // uploaded images
        //'thumbnail' => $model->getAvatarImage(),
        'style' => FileInput::STYLE_IMAGE
    ]);?>
    -->

    <?= $form->field($model, 'status')->dropdownList([
          User::STATUS_ACTIVE => 'Active',
          User::STATUS_DELETED => 'Deleted',
        ],
        ['prompt'=>'Select Status...']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

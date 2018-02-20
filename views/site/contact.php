<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = 'Contacto';
$this->params['breadcrumbs'][] = $this->title;

// parámetros para el sidebar
$this->params['categories'] = $categories;
$this->params['most_visited'] = $most_visited;
?>

        <h1><?= Html::encode($this->title) ?></h1>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Gracias por escribirme. Le responderé en cuanto me sea posible
        </div>
        <?php endif; ?>
<!--
        <p>
            Note that if you turn on the Yii debugger, you should be able
            to view the mail message on the mail panel of the debugger.
            <?php if (Yii::$app->mailer->useFileTransport): ?>
            Because the application is in development mode, the email is not sent but saved as
            a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
            Please configure the <code>useFileTransport</code> property of the <code>mail</code>
            application component to be false to enable email sending.
            <?php endif; ?>
        </p>
-->

        <p>
            Cualquier inquietud o solicitud puede dejarme un correo y responderé en cuanto me sea posible.
        </p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                    <?= $form->field($model, 'name') ?>
                    <?= $form->field($model, 'email') ?>
                    <?php //echo $form->field($model, 'email')->label(false) ?>
                    <?= $form->field($model, 'subject') ?>
                    <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-md-12">{image}</div><div class="col-md-12">{input}</div></div>',
                    ]) ?>
                    <div class="form-group">
                        <?= Html::submitButton(' Enviar', ['class' => 'btn btn-primary glyphicon glyphicon-envelope', 'name' => 'contact-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
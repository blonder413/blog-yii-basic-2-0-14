<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Helper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( !\Yii::$app->user->can('user-admin')) {
            throw new ForbiddenHttpException("Access denied");
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ( !\Yii::$app->user->can('user-admin') and !\Yii::$app->user->can('user-view', ['user' => $model])) {
            throw new ForbiddenHttpException("Access denied");
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate()
     {
         if ( !\Yii::$app->user->can('user-admin')) {
             throw new ForbiddenHttpException("Access denied");
         }

         $model = new User(['scenario' => 'create']);

         if ($model->load(Yii::$app->request->post())) {

           $model->generateAuthKey();
           $model->setPassword($model->password_hash);

           $model->file = UploadedFile::getInstance($model, 'file');
           $model->photo = Helper::limpiaUrl($model->username . '.' . $model->file->extension);

           if ($model->save()) {
             $model->file->saveAs( 'web/img/users/' . $model->photo);
             Yii::$app->session->setFlash('success', Yii::t('app', 'User created successfully'));
             return $this->redirect(['view', 'id' => $model->id]);
           } else {
             print_r($model->getErrors());
             exit;
             $errors = '<ul>';
                foreach ($model->getErrors() as $key => $value) {
                    foreach ($value as $row => $field) {
                        //Yii::$app->session->setFlash("danger", $field);
                        $errors .= "<li>" . $field . "</li>";
                    }
                }
                $errors .= '</ul>';

                //print_r($errors);exit;
                Yii::$app->session->setFlash("danger", $errors);
             return $this->redirect(['index']);
           }
         }

         return $this->render('create', [
             'model' => $model,
         ]);
     }

    /**
     * Updates status for an existing User model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChangeStatus($id)
    {
      if ( !\Yii::$app->user->can('user-change-status')) {
          throw new ForbiddenHttpException("Access denied");
      }

      $model = $this->findModel($id);

      if ($model->status === $model::STATUS_DELETED) {
          $model->status = $model::STATUS_ACTIVE;
      } elseif ($model->status === $model::STATUS_ACTIVE) {
          $model->status = $model::STATUS_DELETED;
      }

      if ($model->save()) {
        Yii::$app->session->setFlash('success', Yii::t('app', 'User udpated successfully'));
      }

      return $this->redirect(['index']);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ( !\Yii::$app->user->can('user-admin') and !\Yii::$app->user->can('user-update', ['user' => $model])) {
            throw new ForbiddenHttpException("Access denied");
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ( !\Yii::$app->user->can('user-admin')) {
            throw new ForbiddenHttpException("Access denied");
        }

        try {
          if ( $model->delete() ) {
            unlink('img/users/' . $model->photo);
          } else {
            $errors = '';
            foreach ($model->getErrors() as $key => $value) {
                foreach ($value as $row => $field) {
                    //Yii::$app->session->setFlash("danger", $field);
                    $errors .= $field . "<br>";
                }
            }
            Yii::$app->session->setFlash("danger", $errors);
          }
          Yii::$app->session->setFlash('success', Yii::t('app', 'User deleted successfully'));
        } catch(\Exception $e) {
          Yii::$app->session->setFlash('warning', Yii::t('app', "User can't be deleted"));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

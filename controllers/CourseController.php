<?php

namespace app\controllers;

use Yii;
use app\models\Course;
use app\models\CourseSearch;
use app\models\Helper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * CourseController implements the CRUD actions for Course model.
 */
class CourseController extends Controller
{
    public $layout = 'adminLTE/main';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
  //                'only' => ['index', 'view', 'create', 'delete','update', 'approve'],
                'rules' => [
  //                    [
  //                        'allow' => true,
  //                        'actions' => ['index'],
  //                        'roles' => ['?'],
  //                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Course models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( !\Yii::$app->user->can('course-list')) {
          throw new ForbiddenHttpException("Access denied");
        }

        $model = new Course(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post())) {


          $model->file = UploadedFile::getInstance($model, 'file');
          $model->image = Helper::limpiaUrl($model->course . '.' . $model->file->extension);


          if ($model->save()) {
            $model->file->saveAs( 'web/img/courses/' . $model->image);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Course created successfully'));
          } else {
  //            print_r($model->getErrors());
  //            exit;
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

        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Course model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if ( !\Yii::$app->user->can('course-view')) {
          throw new ForbiddenHttpException("Access denied");
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Course model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
/*
    public function actionCreate()
    {
        if ( !\Yii::$app->user->can('course-create')) {
          throw new ForbiddenHttpException("Access denied");
        }

        $model = new Course();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
*/
    /**
     * Updates an existing Course model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if ( !\Yii::$app->user->can('course-update')) {
          throw new ForbiddenHttpException("Access denied");
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');

            // si subo otra imagen tengo que remplazar la anterior
            if($model->file) {
                // borro el archivo anterior
                unlink('img/courses/' . $model->photo);
                $model->image = Helper::limpiaUrl($model->course . '.' . $model->file->extension);
            } else {
              // si cambia el nombre del curso renombro la imagen
              if ($model->oldAttributes['course'] !== $model->course) {
                $model->image = Helper::limpiaUrl($model->course . '.png');
                $oldImage = $model->oldAttributes['image'];
                rename('web/img/courses/' . $oldImage, 'web/img/courses/' . $model->image);
              }
            }

            if ($model->save()) {
              if($model->file) {
                $model->file->saveAs( 'img/courses/' . $model->photo);
              }
              Yii::$app->session->setFlash('success', Yii::t('app', 'Course updated successfully'));
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

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Course model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ( !\Yii::$app->user->can('course-delete')) {
          throw new ForbiddenHttpException("Access denied");
        }

        try{
          $model = $this->findModel($id);
          if($model->delete()) {
            unlink('web/img/courses/' . $model->image);
            Yii::$app->session->setFlash("success", Yii::t('app', "Course deleted successfully!"));
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
        } catch (\Exception $e) {
          Yii::$app->session->setFlash("warning", Yii::t('app', "Course can't be deleted!"));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Course model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Course the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Course::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

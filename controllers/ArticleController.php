<?php

namespace app\controllers;

use Yii;
use app\models\Article;
use app\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates status for an existing Article model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChangeStatus($id)
    {
      $model = $this->findModel($id);

      if ($model->status == $model::STATUS_INACTIVE) {
          $model->status = $model::STATUS_ACTIVE;
      } elseif ($model->status == $model::STATUS_ACTIVE) {
          $model->status = $model::STATUS_INACTIVE;
      }

      if ($model->save()) {
        Yii::$app->session->setFlash('success', Yii::t('app', 'Article udpated successfully'));
      } else {
        print_r($model->getErrors());
        exit;
      }

      return $this->redirect(['index']);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
      $model = new Article();

      if ($model->load(Yii::$app->request->post())) {

//            $transaction = Article::getDb()->beginTransaction();
          $transaction = Yii::$app->db->beginTransaction();

          try {

//                setlocale(LC_ALL,"es_CO");

//                $query = "SET lc_time_names = 'es_CO';";
//                $stmt = $this->connexion->query($query);

              $model->detail = html_entity_decode($model->detail);

//                $model->updated_by = Yii::$app->user->id;
//                $model->updated_at = new \yii\db\Expression('NOW()');

              if ($model->save()) {
                  Yii::$app->session->setFlash("success","Article created successfully!");
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

              $transaction->commit();

              return $this->redirect(['index']);
          } catch (\Exception $e) {
              $transaction->rollBack();
              throw $e;
          }

      } else {
          return $this->render('create', [
              'model' => $model,
          ]);
      }
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try{
          $model = $this->findModel($id);
          if($model->delete()) {
            Yii::$app->session->setFlash("success", Yii::t('app', "Article deleted successfully!"));
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
          Yii::$app->session->setFlash("warning", Yii::t('app', "Article can't be deleted!"));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

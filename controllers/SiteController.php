<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\Article;
use app\models\Category;
use app\models\Comment;
use app\models\Course;
use app\models\Helper;
use app\models\Security;
use app\models\Streaming;
use app\models\Type;
use app\models\User;
use yii\db\Expression;

class SiteController extends Controller
{
    public $layout = 'blue/main';
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    /**
     * find an article by slug
     * @param string $slug seo-slug of article
     * @return object
     */
    public function actionArticle($slug)
    {
//        $this->layout = 'blue/main';
        $article = Article::find()->where('slug = :slug', [':slug' => $slug])->one();
        
        if (sizeof($article) == 0) {
            throw new NotFoundHttpException();
        }

        $course = Course::findOne($article->course_id);

        $nextArticle    = Article::find()->where(
                            "course_id = :course_id AND number = :number",
                            [":course_id" => $article->course_id, ":number" => $article->number + 1]
                        )->one();

        $prevArticle    = Article::find()->where(
                            "course_id = :course_id AND number = :number",
                            [":course_id" => $article->course_id, ":number" => $article->number - 1]
                        )->one();

        $categories = Category::find()->orderBy('category asc')->all();

        // AUMENTAR EL CAMPO visit_count en 1
        if (Yii::$app->user->isGuest) {
            $article->visit_counter += 1;
//            $article->updateCounters(["visit_counter" => 1]);
            $article->save();
        }

        $tags = explode(", ", $article->tags);

        $comment = new Comment(['scenario' => 'comment']);

        $most_visited = Article::find()->orderBy('visit_counter desc')->limit(5)->all();

//        if ( Yii::$app->request->isAjax && $comment->load(Yii::$app->request->post()) ) {

        if ($comment->load(Yii::$app->request->post())) {

            $comment->date          = new Expression('NOW()');
            $comment->article_id    = $article->id;
            $comment->client_ip     = Helper::getRealIP();
            $comment->client_port   = $_SERVER['REMOTE_PORT'];
            $comment->rel           = "nofollow";
            
//            if (!$comment->validate()) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return \yii\widgets\ActiveForm::validate($comment);
//            }

            $blackList = [
                '23.95.201.236',    '23.104.161.65',    '46.161.9.32',      '91.108.177.114',
                '91.108.177.161',   '104.129.13.141',   '104.160.13.43',
                '107.151.152.26',
                '107.151.152.95',   '107.172.241.115',  '107.175.159.60',
                '114.98.232.30',    '114.98.245.207',
                '124.73.2.106',
                '178.32.12.117',    '181.214.37.131',
                '188.143.232.10',   '195.154.35.47',
                '198.52.168.254',
            ];
            
            $oldComment = Comment::find()->where(
                "email = :email AND article_id = :article AND comment = :comment",
                [ ":email" => Security::mcrypt($comment->email), ":article" => $comment->article_id, ":comment" => $comment->comment ]
            )->count();
            
            if ( in_array($comment->client_ip, $blackList) or $oldComment > 0 ) {
                $comment = new Comment(['scenario' => 'comment']);
                Yii::$app->session->setFlash("success", "Gracias por su opinión. Su comentario será publicado una vez que sea moderado!");
            } elseif ($comment->save()) {
                $comment = new Comment(['scenario' => 'comment']);
                Yii::$app->session->setFlash("success", "Gracias por su opinión. Su comentario será publicado una vez que sea moderado!");
            } else {
                Yii::$app->session->setFlash("error", "Su comentario no pudo ser registrado!");
            }
        }

        return $this->render('article', [
            'article'       => $article,
            'categories'    => $categories,
            'comment'       => $comment,
            'course'        => $course,
            'most_visited'  => $most_visited,
            'nextArticle'   => $nextArticle,
            'prevArticle'   => $prevArticle,
            'tags'          => $tags,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Article::find();

        $pagination = new Pagination([
            'defaultPageSize'   => 20,
            'totalCount'        => $query->count(),
        ]);

        $articles = $query->orderBy('id desc')
                    ->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->all();

        $categories = Category::find()->orderBy('category asc')->all();

        $most_visited = Article::find()->orderBy('visit_counter desc')->limit(5)->all();

        return $this->render('index', [
            'articles'      => $articles,
            'categories'    => $categories,
            'most_visited'  => $most_visited,
            'pagination'    => $pagination,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
//        $this->layout = 'blue/contact';
        $model = new ContactForm(['scenario' => 'contact']);

        $categories = Category::find()->orderBy('category asc')->all();

        $most_visited = Article::find()->orderBy('visit_counter desc')->limit(5)->all();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->contact(Yii::$app->params['supportEmail'])) {
                Yii::$app->session->setFlash('contactFormSubmitted');
            } else {
                Yii::$app->session->setFlash("error", "el mensaje no pudo ser enviado");
            }
            
            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
                'categories'    => $categories,
                'most_visited'  => $most_visited,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        $categories = Category::find()->orderBy('category asc')->all();

        $most_visited = Article::find()->orderBy('visit_counter desc')->limit(5)->all();
        return $this->render('about', [
            'categories'    => $categories,
            'most_visited'  => $most_visited,
        ]);
    }
    
    /**
     * find all courses
     * @return objects array
     */
    public function actionAllCourses()
    {
        $categories     = Category::find()->orderBy('category asc')->all();
        $most_visited   = Article::find()->orderBy('visit_counter desc')->limit(5)->all();
        $courses        = Course::find()->all();

        return $this->render(
            'all-courses',
            [
                'categories'    => $categories,
                'courses'       => $courses,
                'most_visited'  => $most_visited,
            ]
        );
    }
    
    /**
     * Displays portfolio page.
     *
     * @return string
     */
    public function actionPortfolio()
    {
        $categories = Category::find()->orderBy('category asc')->all();
        $most_visited = Article::find()->orderBy('visit_counter desc')->limit(5)->all();

        return $this->render(
            'portfolio',
            [
                'categories'    => $categories,
                'most_visited'  => $most_visited,
            ]
        );
    }
    
    /**
     * Displays streaming page.
     *
     * @return string
     */
    public function actionStreaming()
    {
        $now = date("Y-m-d H:i:s");

        $streaming = Streaming::find()
                    ->where('start <= :now', [':now'=>$now])
                    ->andWhere("end >= :now", [":now"=>$now])
                    ->one();

        $articles = $most_visited = Article::find()->where('category_id = 15')->orderBy('rand()')->limit(5)->all();
        $categories = Category::find()->orderBy('category asc')->all();
        $most_visited = Article::find()->orderBy('visit_counter desc')->limit(5)->all();

        $next_streaming = Streaming::find()
                    ->where('start > :now', [':now'=>$now])
                    ->orderBy('start asc')
                    ->one();

        return $this->render(
            'streaming',
            [
                'articles'          => $articles,
                'categories'        => $categories,
                'most_visited'      => $most_visited,
                'next_streaming'    => $next_streaming,
                'streaming'         => $streaming,
            ]
        );
    }
}

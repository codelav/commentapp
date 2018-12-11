<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Page;

use app\modules\comment\src\widget\Comment as CommentWidget;

class SiteController extends Controller
{
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
                    'logout' => ['get'],
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', ['asd' => 'sda']);
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

    public function actionAuth()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->load(Yii::$app->request->post());
        $model->login();

        // if ($model->load(Yii::$app->request->post()) && $model->login()) {
        //     $widgetParams = [
        //         'model' => Yii::$app->request->post('model'),
        //         'model_id' => Yii::$app->request->post('model_id'),
        //     ];

        //     return CommentWidget::widget($widgetParams);
        // }

        $widgetParams = [
            'model' => Yii::$app->request->post('model'),
            'model_id' => Yii::$app->request->post('model_id'),
        ];

        return CommentWidget::widget($widgetParams);

        // $model->password = '';
        // return $this->render('login', [
        //     'model' => $model,
        // ]);
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
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        // $model = User::find()->where(['username' => 'user'])->one();
        // if (empty($model)) {
        //     $user = new User();
        //     $user->username = 'user';
        //     $user->email = 'user@dev.com';
        //     $user->setPassword('password');
        //     $user->generateAuthKey();
        //     if ($user->save()) {
        //         echo 'good';
        //     }
        // }
        $page = Page::find()->where(['id' => 1])->one();
        return $this->render('page', ['model' => $page]);
    }

    public function actionDisplay($id)
    {
        $page = Page::find()->where(['id' => $id])->one();

        if (!$page) {
            throw new \yii\web\NotFoundHttpException();
        }

        return $this->render('page', ['model' => $page]);
    }

    public function render($view, $params = array())
    {
        $this->view->params['mmenu'] = Page::getMenuList();

        return parent::render($view, $params);
    }
}

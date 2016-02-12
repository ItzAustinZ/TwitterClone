<?php

namespace frontend\controllers;

use Yii;
use app\models\Tweet;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use common\models\User;
use app\models\MediaConnections;

use yii\web\UploadedFile;

use common\components\AccessRule;
use yii\web\Session;

require_once '../../third/Cloudinary.php';
require_once '../../third/Uploader.php';
require_once '../../third/Api.php';

/**
 * TweetController implements the CRUD actions for Tweet model.
 */
class TweetController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['create', 'update', 'delete', 'index'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_SUPER,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tweet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Tweet::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tweet model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionViewAll()
    {
        $tweets = Tweet::find()->orderBy('timestamp DESC')->all();
        return $this->render('gridview', ['tweets' => $tweets,]);
    }
    
    public function actionViewUser($username)
    {
        $tweets = Tweet::find()->where(['owner' => $username,])->orderBy('timestamp DESC')->all();
        return $this->render('gridview', ['tweets' => $tweets,]);
    }

    /**
     * Creates a new Tweet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tweet();

        if ($model->load(Yii::$app->request->post()))
        {          
            $model->owner = Yii::$app->user->identity->username;
            $date = new \DateTime();
            $model->timestamp = $date->getTimestamp();
            if($model->save()) {
                //Upload images if need be.
                $image = UploadedFile::getInstances($model, 'image');
                \Cloudinary::config(array( 
                    "cloud_name" => "dxqmggd5a", 
                    "api_key" => "314154111631994", 
                    "api_secret" => "KE-AgYwX8ecm8N2omI22RDVmFv4" 
                ));
                foreach($image as $file)
                {   
                    $uploadResult = \Cloudinary\Uploader::upload($file->tempName);
                    $myConnection = new MediaConnections();
                    $myConnection->tweet = $model->id;
                    $myConnection->url = $uploadResult['url'];
                    $myConnection->timestamp = $model->timestamp;
                    $myConnection->save();
                }
                User::findByUsername($model->owner)->createTweet();
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    public function actionToggleEncryptedEyes()
    {
        $session = new Session();
        $session->open();
        $session['eyes_encrypted'] = !$session['eyes_encrypted'];
        $session->close();
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionToggleAdminEyes()
    {
        $session = new Session();
        $session->open();
        $session['eyes_admin'] = !$session['eyes_admin'];
        $session->close();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Updates an existing Tweet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->user->isGuest || ((Yii::$app->user->identity->username != $model->owner) && (Yii::$app->user->identity->role < User::ROLE_ADMIN)))
        {
            throw new \yii\web\HttpException(403, 'Cannot modify others messages.');
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tweet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->user->isGuest || ((Yii::$app->user->identity->username != $model->owner) && (Yii::$app->user->identity->role < User::ROLE_ADMIN)))
        {
            throw new \yii\web\HttpException(403, 'Cannot delete others messages.');
        }
        
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tweet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tweet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tweet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

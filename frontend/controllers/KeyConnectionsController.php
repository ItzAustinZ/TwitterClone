<?php

namespace frontend\controllers;

use Yii;
use app\models\KeyConnections;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use common\models\User;

use common\components\AccessRule;

/**
 * KeyConnectionsController implements the CRUD actions for KeyConnections model.
 */
class KeyConnectionsController extends Controller
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
     * Lists all KeyConnections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => KeyConnections::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KeyConnections model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KeyConnections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KeyConnections();

        if ($model->load(Yii::$app->request->post())){
            $model->owner = Yii::$app->user->identity->id;
            //Check if key already exists. If so, just redirect.
            if(KeyConnections::find()->where(['text' => $model->text, 'owner' => $model->owner,])->exists())
            {
                return $this->redirect(Yii::$app->request->referrer);
            }
            $date = new \DateTime();
            $model->timestamp = $date->getTimestamp();
            if($model->save(false)) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KeyConnections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->user->isGuest || ((Yii::$app->user->identity->id != $model->owner) && (Yii::$app->user->identity->role < User::ROLE_ADMIN)))
        {
            throw new \yii\web\HttpException(403, 'Cannot modify others keys.');
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing KeyConnections model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->user->isGuest || ((Yii::$app->user->identity->id != $model->owner) && (Yii::$app->user->identity->role < User::ROLE_ADMIN)))
        {
            throw new \yii\web\HttpException(403, 'Cannot delete others keys.');
        }
        $model->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the KeyConnections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KeyConnections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KeyConnections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

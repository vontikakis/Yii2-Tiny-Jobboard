<?php

/**
 * JobController class file.
 *
 * @package   General
 * @author    Vasilis Vontikakis <vasillis@gmail.com>
 * @copyright 2018 Vasilis Vontikakis
 * @license   GPL
 * @link      https://www.vontikakis.com/
 * 
 */

namespace app\controllers;

use Yii;
use app\models\Job;
use app\models\JobSearch;
use app\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * JobController implements the CRUD actions for Job model.
 */
class JobController extends Controller
{

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
     * Lists all Job models in admin panel.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->isGuest) {

            $searchModel = new JobSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                
            ]);

        } else {

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
   
    /**
     * Jobs in homes page group by categories
     * @return mixed
     */
    public function actionList()
    {
        
        $categories = Category::find()->joinWith(['jobs'])->orderBy('tb_jobs.created_at desc')->all();

        return $this->render('list', [
            'categories' => $categories,
        ]);
    }


    /**
     * Displays a view of job in admin or of is public
     * @param integer $id
     * @param string $slug
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $slug = false)
    {

        $model = $this->findModel($id);
        
        if($model->is_public == true || !Yii::$app->user->isGuest) {

            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);

        } else {

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Creates a new Job model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionNew()
    {
        $model = new Job();

        $categories = Category::activeCategories();

        if ($model->load(Yii::$app->request->post())) {

            $model->logo = UploadedFile::getInstance($model, 'logo');
           

            if($model->save() && $model->upload()) {
        
                Yii::$app->mailer->compose()
                    ->setTo(Yii::$app->params['adminEmail'])
                    ->setFrom([Yii::$app->params['notificationEmail'] => 'notification'])
                    ->setSubject('new job for approval')
                    ->setTextBody('new job for approval')
                    ->send();
                
                Yii::$app->session->setFlash('success',Yii::t('app','Job was posted please wait for approve.'));
               
            }          

            return $this->redirect(['job/list']);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing Job model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->user->isGuest) {
            $model = $this->findModel($id);
            
            $categories = Category::activeCategories();


            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
                'categories' => $categories
            ]);
        } else {

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    } 

    /**
     * Deletes an existing Job model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionDelete($id)
    {
        if(!Yii::$app->user->isGuest) {
            
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    } 

    /**
     * Finds the Job model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Job the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Job::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

<?php

namespace app\controllers;

use Yii;
use app\models\Job;
use app\models\JobSearch;
use app\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;


/**
 * CategoryController class file.
 *
 * @package   General
 * @author    Vasilis Vontikakis <vasillis@gmail.com>
 * @copyright 2018 Vasilis Vontikakis
 * @license   GPL
 * @link      https://www.vontikakis.com/
 */

class CategoryController extends Controller
{


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'deletemail' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['list'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['list', 'index','delete','create','update','view'],
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * show jobs by category
     *
     * @param  integer $id   id number of category
     * @param  string  $slug slug name 
     * @return mixed       
     */
    public function actionList($id, $slug = false)
    {

        $categories = Category::find()->where(['id' => $id])->all();
        
        return $this->render(
            'list', [
            'categories' => $categories,
            ]
        );
    }

        /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
       
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {

        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
}


}

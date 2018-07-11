<?php

namespace app\controllers;

use Yii;
use app\models\Job;
use app\models\JobSearch;
use app\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


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

}

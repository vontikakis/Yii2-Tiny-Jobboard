<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Job */

$this->title = Yii::t('app', 'Create a Job Ad ');
?>
<div class="job-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories
    ]) ?>

</div>

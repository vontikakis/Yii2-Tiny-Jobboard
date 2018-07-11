<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Job */
$this->title = $model->title;
?>
<div id="job-show">
    <div class="row">
        <div class="col-md-6">
            <?= Html::a('← '.Yii::t('app','back to all jobs'), ['job/list'], ['class' => 'back-to-all-jobs']) ?>
        </div>
        <div class="col-md-6">
            <?= Html::a('See more '.$model->category->name.' jobs →',['category/list', 'id'    => $model->category->id, 'slug'  => $model->category->slug], ['class' => 'back-to-all-category-jobs'] ) ?>
        </div>
    </div>    
    <div class="row">
        <div class="header col-md-9">
            <h1><?= $model->title ?></h1>
            <h3><span>Posted On <?= \Yii::$app->formatter->asDate($model->created_at, 'php: M d') ?></span></h3>

            <h2>
                <div class="company"><?= $model->company ?></div>
                <div class="location">Location: <?= $model->location ?></div>
                <a href="<?= $model->url ?>"><?= $model->url ?></a>
            </h2>
        </div>
        <div class="logo col-md-3">
            <? if(!is_null($model->logo)): ?>
                    <?= Html::img('/storage/'.$model->id.'/thumb_'.$model->logo);?>
            <? endif; ?>
        </div>
    </div>
    <div class="clear"></div>
    <hr>
    <?= $model->company ?>
    <div class="row">
        <div class="col-md-9">
            <?= $model->description ?>
        </div>
        <div class="col-md-3">

        </div>
    </div>
    <div class="row">
        <div class="col-md-9 apply">
            <h3> Apply for a job</h3>
            <?= $model->instructions ?>
        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
?>
<div id="job-list" class="category-job-list">
    <? foreach($categories as $category): ?>
            <h2><?= Html::a($category->name, ['category/index','id'=>$category->id,'slug'=>$category->slug]) ?></h2>
            <ul>
                <? foreach ($category->activeJobs as $job):  ?>
                      <li>
                           <span class="company"><?= $job->company ?></span>
                           <?= Html::a('<span class="title">'.$job->title.'</span>', ['job/view', 'id' => $job->id, 'slug' => $job->slug]) ?>
                           <span class="created-at"><?= \Yii::$app->formatter->asDate($job->created_at, 'php: M d') ?></span>
                      </li>
                <?  endforeach; ?>
            </ul>
    <? endforeach; ?>
	<div class="row">
		<div class="col-md-12">
			 <?= Html::a('← back to all jobs', ['job/list'], ['class' => 'back-to-all-jobs']) ?>
		</div>
	</div>      
</div>
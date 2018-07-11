<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Jobs');
?>

<div class="hero">
        <div class="hero-text">
            <h1 class="hero-title">Find people for marketing, operations and tech stuffs </h1>
            <p class="hero_helper">hire the best ecommerce experts to level up your business</p></div>
            <div class="Hero__actions">
            <?=  Html::a(Yii::t('app','Post A Job'), 'job/new', ['class' => 'btn btn-success', 'style' => 'width:220px; margin-top:0px; font-size:18px;']) ?>
        </div>
    </div>
<div id="job-list">
    <? foreach($categories as $category): ?>
            
            <? $countActiveJobs = $category->countActiveJobs ?>

            <? if($countActiveJobs > 0): ?>

                  <h2><?= Html::a($category->name, ['category/list','id' => $category->id, 'slug' => $category->slug]) ?></h2>

                  <ul>
                      <? foreach ($category->recentJobs as $job):  ?>
                            <li>
                                 <span class="company"><?= $job->company ?></span>
                                 <?= Html::a('<span class="title">'.$job->title.'</span>', ['job/view', 'id' => $job->id, 'slug' => $job->slug]) ?>
                                 <span class="created-at"><?= \Yii::$app->formatter->asDate($job->created_at, 'php: M d') ?></span>
                            </li>
                      <?  endforeach; ?>

                      <li><?= Html::a('View all '.$countActiveJobs.' '.$category->name.' Jobs', ['category/list', 'id'=>$category->id, 'slug' => $category->slug], ['class' => 'total-active-jobs']) ?></li>             
                  </ul>
            <? endif; ?>
    <? endforeach; ?>
</div>

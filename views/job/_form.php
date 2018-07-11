<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Job */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('js/tinymce/tinymce.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJs('
       
    tinymce.init({
        menubar:false,
        statusbar: false,
        selector: "textarea#job-description, textarea#job-instructions",
        entity_encoding : "raw",    
        autoresize_min_height: 280,
        autosave_restore_when_empty : true,
        content_css : "/css/editor.css",
        theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
        font_size_style_values : "10px,12px,13px,14px,16px,18px,20px",
        plugins: "autolink, autoresize, link, save, code, preview, fullscreen, contextmenu, autosave, image, textcolor",
        toolbar: "bold italic  | bullist numlist ",
    });

    $("#send-email").click(function(){

        tinyMCE.triggerSave();
    });


', \yii\web\VIEW::POS_END); 
?>

<div class="job-form">

    <?php $form = ActiveForm::begin([
                     'layout' => 'horizontal',
                     'class' => 'form-horizontal',
                      'fieldConfig' => [
                            'horizontalCssClasses' => [
                                'label' => 'col-sm-2',
                                'offset' => '',
                                'wrapper' => 'col-sm-8',
                                'hint' => 'col-sm-offset-2 col-sm-8'
                            ],
                        ],
                       'options' => ['enctype' => 'multipart/form-data']
                     ]); ?>

    <h2>Tell us the details about the job position</h2>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->hint('Graphic Designer or Web Developer') ?>
    
    <?= $form->field($model, 'category_id')->radioList($categories); ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true])->hint('Athens, London') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'instructions')->textarea(['rows' => 6])->hint('Example: Send a resume to bill@company.com') ?>

   <h2>Tell us about the company</h2>

    <?= $form->field($model, 'company')->textInput(['maxlength' => true])->hint('Enter your company or organization’s name.') ?>

    <? // $form->field($model, 'company_statement')->textInput(['maxlength' => true])->hint('Description of your company') ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true])->hint('Example: https://mybusiness.com/careers/apply') ?>

    <?= $form->field($model, 'logo')->fileInput()->hint('Optional — Your company logo will appear at the top of your listing. It\'s highly recommended to use either your Twitter or Facebook avatar.')   ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true])->hint('This is where we’ll send confirmation email. ') ?>

    <?   if(!Yii::$app->user->isGuest): ?>


            <?= $form->field($model, 'expired_at')->widget(DatePicker::class, [
                  'language' => 'en',
                  'dateFormat' => 'yyyy-MM-dd',
            ])  ?>

            <?= $form->field($model, 'is_public')->checkbox() ?>
    
    <?   endif; ?>
    <div class="row">
        <div class="col-md-offset-4">
            <?= Html::submitButton(Yii::t('app', 'Publish the Job Ad'), ['class' => 'btn-lg btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

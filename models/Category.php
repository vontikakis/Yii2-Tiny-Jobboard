<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%tb_categories}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TbJobs[] $tbJobs
 */
class Category extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tb_categories}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'slug' => Yii::t('app', 'Slug'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getJobs()
    {
        return $this->hasMany(Job::className(), ['category_id' => 'id']);
    }

    private function criteriaActiveJobs()
    {
        return $this->hasMany(Job::className(), ['category_id' => 'id'])->where('is_public = :is_public and expired_at >= :date', [':is_public' => 1, ':date'=> date('Y-m-d H:i:s')]);
    }

    public function getRecentJobs()
    {
        return $this->criteriaActiveJobs()->limit(Yii::$app->params['recentJobs'])->orderBy('created_at desc');
    }

    public function getActiveJobs()
    {
        return $this->criteriaActiveJobs()->orderBy('created_at desc');

    }

    public function getCountActiveJobs()
    {
        return $this->criteriaActiveJobs()->count();
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('UTC_TIMESTAMP()'),
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
            ],
        ];
    }

    public static function activeCategories() 
    {

        $data  = ( new \yii\db\Query())->select('id, name')->from(self::tableName())->orderBy('name')->all();

        if(!empty($data)) {
                   
            return ArrayHelper::map($data, 'id', 'name');
            
        
        } else {
        
            return null;
        }
    }
}

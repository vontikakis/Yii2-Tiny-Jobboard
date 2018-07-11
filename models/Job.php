<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * This is the model class for table "{{%tb_jobs}}".
 *
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $company
 * @property string $company_statement
 * @property string $url
 * @property string $logo
 * @property string $email
 * @property string $location
 * @property string $instructions
 * @property int $is_public
 * @property string $slug
 * @property int $category_id
 * @property string $expired_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TbCategories $category
 */
class Job extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tb_jobs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'instructions'], 'string'],
            [['is_public', 'category_id'], 'integer'],
            [['title', 'company', 'category_id', 'email' , 'location' , 'description', 'instructions'], 'required'],
            [['logo', 'expired_at', 'created_at', 'updated_at', 'token'], 'safe'],
            [['email'], 'email'],
            [['title', 'company', 'company_statement', 'url', 'email', 'location', 'slug'], 'string', 'max' => 255],
            [['logo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Job Title'),
            'description' => Yii::t('app', 'Job Description'),
            'company' => Yii::t('app', 'Company Name'),
            'company_statement' => Yii::t('app', 'Company Statement'),
            'url' => Yii::t('app', 'Url'),
            'logo' => Yii::t('app', 'Logo'),
            'email' => Yii::t('app', 'Email'),
            'location' => Yii::t('app', 'Location'),
            'instructions' => Yii::t('app', 'How People Apply?'),
            'is_public' => Yii::t('app', 'Is Public'),
            'slug' => Yii::t('app', 'Slug'),
            'category_id' => Yii::t('app', 'Category'),
            'expired_at' => Yii::t('app', 'Expired At'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
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
                'attribute' => 'title',
                'slugAttribute' => 'slug',
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
        
            if ($insert) {

                $this->token = hash('sha256', $this->email.time().rand(1000, 9999));
            }
        
            return true;
        }

        return false;
    }

    public function upload()
    {
        if(is_null($this->logo)) {

            return true;
        }

        $path = Yii::getAlias('@webroot').DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR;


        if(!is_dir($path.$this->id)) {

            mkdir($path.$this->id, 0755);
        }

        $newPath = $path.$this->id.DIRECTORY_SEPARATOR;

        if ($this->validate()) { 
             
                $fileName = $this->logo->baseName.'.'.$this->logo->extension;

                $this->logo->saveAs($newPath.$fileName);
                
                $imagine = Image::getImagine()->open($newPath.$fileName)->thumbnail(new Box(190, 190))->save($newPath.'thumb_'.$fileName, ['quality' => 100]);

                $this->logo = null;

                $this->logo =  $fileName;
            
            return true;
        
        } else {
            
            return false;
        }
    }
}

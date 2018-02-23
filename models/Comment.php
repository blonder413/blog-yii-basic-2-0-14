<?php

namespace app\models;
use app\models\Security;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $web
 * @property string $rel
 * @property string $comment
 * @property string $date
 * @property int $article_id
 * @property int $status
 * @property string $client_ip
 * @property string $client_port
 *
 * @property Article $article
 */
class Comment extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $verifyCode;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'web'], 'trim'],
            [['name', 'email', 'comment', 'date', 'article_id'], 'required'],
            [['comment'], 'string'],
            [['date'], 'safe'],
            [['article_id'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['email', 'web'], 'string', 'max' => 100],
            ['email', 'email', 'on' => 'comment'],
            ['web', 'url', 'defaultScheme' => 'http', 'message' => 'Por favor introduzca la URL completa, ej: www.blonder413.com'],
            [['rel'], 'string', 'max' => 20],
            [['client_ip'], 'string', 'max' => 15],
            [['client_port'], 'string', 'max' => 5],
            [['verifyCode'], 'captcha', 'on'=>'comment'],
            [['status'], 'default', 'value' => self::STATUS_INACTIVE],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'web' => Yii::t('app', 'Web'),
            'rel' => Yii::t('app', 'Rel'),
            'comment' => Yii::t('app', 'Comment'),
            'date' => Yii::t('app', 'Date'),
            'article_id' => Yii::t('app', 'Article ID'),
            'status' => Yii::t('app', 'Status'),
            'client_ip' => Yii::t('app', 'Client Ip'),
            'client_port' => Yii::t('app', 'Client Port'),
            'verifyCode'    => Yii::t('app', 'Verify Code'),
        ];
    }

    public function beforeSave($insert)
    {
        /*
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->email = Security::mcrypt($this->email);
            }

            return true;
        }
        */

        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->email = Security::mcrypt($this->email);
            }

            return true;
        } else {
            return false;
        }

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }
}

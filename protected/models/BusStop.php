<?php

/**
 * This is the model class for table "busStop".
 *
 * The followings are the available columns in table 'busStop':
 * @property integer $id
 * @property integer $user_id
 * @property string $value
 * @property string $datetime
 *
 * The followings are the available model relations:
 */
class ECGMeasurement extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'busStop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			// The following fields are required
			array('user_id, value, datetime', 'required'),
			// Numerical values
			array('user_id', 'numerical', 'integerOnly' => true,),
			// Datetime should be the following format
			array('datetime', 'date', 'format'=>'yyyy-MM-dd HH:mm:ss'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ECGMeasurement the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

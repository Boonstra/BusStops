<?php

/**
 * This is the model class for table "busstops".
 *
 * The followings are the available columns in table 'busstops':
 * @property integer $id
 * @property integer $halteNummer_overig
 * @property double  $GPS_Longitude
 * @property double  $GPS_Latitude
 * @property string  $naam
 * @property double  $opt_dieptehaltekom
 * @property double  $opt_halteerlengte1
 * @property double  $opt_halteerlengte2
 * @property double  $opt_halteerlengte3
 * @property string  $opt_inrijhoek
 * @property string  $opt_uitrijhoek
 * @property string  $opt_perronband
 * @property double  $opt_perronhoogte
 * @property double  $opt_perronbreedte
 * @property double  $opt_perronlengte
 * @property double  $opt_barrierevrije_doorgang
 * @property string  $opt_hellingshoek
 * @property double  $opt_breedteaanlooproute
 * @property double  $opt_hoogteverschilperron
 * @property integer $opt_markeringperronrand
 * @property integer $opt_geleidelijn
 * @property integer $opt_aanwezigheidabri
 * @property double  $opt_afstandperronabri
 * @property string  $opt_halteaanduiding
 * @property string  $opt_reisinformatie
 * @property integer $opt_infoomgeving
 * @property integer $opt_zitgelegenheid
 * @property string  $opt_verlichting
 * @property integer $opt_afvalbak
 * @property integer $opt_fietsparkeervoorziening
 * @property string  $category
 * @property string  $city
 *
 * The followings are the available model relations:
 */
class BusStop extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'busstops';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			// Required
			array('id, halteNummer_overig, GPS_Longitude, GPS_Latitude', 'required'),
			// Integer
			array('
				id,
				halteNummer_overig,
				GPS_Longitude,
				GPS_Latitude,
				opt_dieptehaltekom,
				opt_halteerlengte1,
				opt_halteerlengte2,
				opt_halteerlengte3,
				opt_perronhoogte,
				opt_perronbreedte,
				opt_perronlengte,
				opt_barrierevrije_doorgang,
				opt_breedteaanlooproute,
				opt_hoogteverschilperron,
				opt_markeringperronrand,
				opt_geleidelijn,
				opt_aanwezigheidabri,
				opt_afstandperronabri,
				opt_infoomgeving,
				opt_zitgelegenheid,
				opt_afvalbak,
				opt_fietsparkeervoorziening',
			'numerical'),
			// The following rule is used by search().
			array('id', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BusStop the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function useTypeCasting()
	{
		return true;
	}
}

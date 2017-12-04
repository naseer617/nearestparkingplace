<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "garages".
 *
 * @property integer $id
 * @property string $owner
 * @property integer $hourly
 * @property string $currency
 * @property string $email
 * @property string $country
 * @property string $lat
 * @property string $lng
 */
class Garage extends \yii\db\ActiveRecord
{
    const RADIUS_KM = 6371;

    const RADIUS_MILES = 3956;

    const DISTANCE_KM = "km";

    const DISTANCE_MILES = "miles";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'garages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner'], 'string'],
            [['hourly'], 'integer'],
            [['lat', 'lng'], 'required'],
            [['lat', 'lng'], 'number'],
            [['currency', 'email'], 'string', 'max' => 255],
            [['country'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner' => 'Owner',
            'hourly' => 'Hourly',
            'currency' => 'Currency',
            'email' => 'Email',
            'country' => 'Country',
            'lat' => 'Lat',
            'lng' => 'Lng',
        ];
    }

    private function _getEarthRadius($unit)
    {
        if($unit == self::DISTANCE_KM){
            return self::RADIUS_KM;
        }else{
            return self::RADIUS_MILES;
        }
    }

    /**
     * @param $earthsRadius
     * @param $lat
     * @param $lng
     * @access
     * @return string
     */
    private function _getDistanceFormula($earthsRadius, $lat, $lng)
    {
        return "( ".$earthsRadius." * acos( cos( radians(".$lat.") )
              * cos( radians( garages.lat ) )
              * cos( radians( garages.lng ) - radians(".$lng.") )
              + sin( radians(".$lat.") )
              * sin( radians( garages.lat ) ) ) ) AS distance ";
    }

    /**
     * @param $lat
     * @param $lng
     * @param $proximity
     * @param $unit
     * @access
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getByLatLong($lat = 0, $lng = 0, $proximity = 150, $unit = 'km')
    {
        $earthsRadius = $this->_getEarthRadius($unit);

        return self::find()->select([$this->_getDistanceFormula($earthsRadius, $lat, $lng),'id','country','owner','email','hourly','currency', 'lat','lng'])
            ->having(['<=', 'distance', $proximity])
            ->asArray()
            ->all();
    }

    /**
     * @param $lat
     * @param $lng
     * @param $proximity
     * @param $unit
     * @access
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getByOwer($lat = 0, $lng = 0, $owner, $proximity = 150, $unit = 'km')
    {
        $earthsRadius = $this->_getEarthRadius($unit);

        return self::find()->select([$this->_getDistanceFormula($earthsRadius, $lat, $lng),'id','country','owner','email','hourly','currency', 'lat','lng'])
            ->where(['like','owner', $owner])
            ->having(['<=', 'distance', $proximity])
            ->asArray()
            ->all();
    }
}

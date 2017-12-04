<?php
/**
 * @creator   Naseer Ahmad <naseer@bt.tn>
 * @created  2017-02-08
 * @package
 * @category
 */

namespace app\models;

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use yii\base\Exception;

/**
 * For current assignment, this class is mainly used to make curl call for fetching geo coordinates by country name.
 *
 *Later on it can be used to generate Maps programatically via php.
 *
 * Class MapWrapper
 * @package app\models
 * @category
 * @subpackage
 * @author          Naseer Ahmad <naseer@bt.tn>
 * @version         1.0.0
 */
class MapWrapper
{
    const ZOOM_LEVEL_COUNTRY = 8;

    const ZOOM_LEVEL_GEOLOC = 10;

    const ZOOM_LEVEL_OWNER = 2;

    const GOOGLE_API_KEY = "AIzaSyCqHtoANjbEjDsOhiJy6-1D1VBKOhR1Q7k";

    const GEOCODE_BASE_URL = "https://maps.googleapis.com/maps/api/geocode/json";

    /**
     * @var int
     */
    private $_centerLat = 0;

    /**
     * @var int
     */
    private $_centerLng = 0;

    /**
     * @var Map
     */
    private $_mapObj = null;


    /**
     *
     */
    public function __construct()
    {

    }

    public function getCenterLat()
    {
        return $this->_centerLat;
    }

    public function getCenterLng()
    {
        return $this->_centerLng;
    }

    /**
     * @param $country
     * @access
     * @return bool
     */
    function initMapByCountry($country)
    {
        /**
         * Getting Geo location of country
         */
        $qry_str = "?address=".$country."&sensor=false&key=".self::GOOGLE_API_KEY;

        $ch = curl_init();

        // Set query data here with the URL
        curl_setopt($ch, CURLOPT_URL, self::GEOCODE_BASE_URL . $qry_str);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '3');
        $content = json_decode(trim(curl_exec($ch)),true);
        curl_close($ch);

        if(isset($content['results'][0]['geometry']['location'])){
            /**
             * Geo location found, creating map object
             */

            $this->initMapByLatLng(
                $content['results'][0]['geometry']['location']['lat'],
                $content['results'][0]['geometry']['location']['lng']
            );

            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $lat
     * @param $lng
     * @access
     * @return bool
     */
    function initMapByLatLng($lat, $lng)
    {
        $this->_centerLat = $lat;
        $this->_centerLng = $lng;

        //$this->_initMap();

        return true;
    }

    /**
     * Initialises Map Object
     * @access private
     */
    private function _initMap()
    {
        $coord = new LatLng(['lat' => $this->_centerLat, 'lng' => $this->_centerLng]);
        $this->_mapObj = new Map([
            'center' => $coord,
            'zoom' => self::ZOOM_LEVEL_COUNTRY,
        ]);

        // Lets add a marker now
        $marker = new Marker([
            'position' => $coord,
            'title' => '',
            'icon' => 'images/user_marker.png'
        ]);

        // Add marker to the map
        $this->_mapObj->addOverlay($marker);
    }

    /**
     * Creating Map js and html in php and displaying it in view.
     * NOTE : This method is not used anywhere YET.
     * @param $data
     * @access
     * @return Map
     * @throws Exception
     */
    public function buildMap($data)
    {
        if(is_null($this->_mapObj)){
            throw new Exception('Map is not initialised');
        }

        foreach ($data as $dataPoint) {
            $point = new LatLng([
                'lat' => $dataPoint['lat'],
                'lng' => $dataPoint['lng']
            ]);

            // Lets add a marker now
            $marker = new Marker([
                'position' => $point,
                'title' => '',
                'icon' => 'images/garage_marker.png'
            ]);

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                    'content' => '<p>' . $dataPoint['owner'] . '</p><p>' . $dataPoint['distance'] . ' km</p>'
                ])
            );

            // Add marker to the map
            $this->_mapObj->addOverlay($marker);
        }

        return $this->_mapObj;
    }


}
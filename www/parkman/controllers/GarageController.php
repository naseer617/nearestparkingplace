<?php
/**
 * @SWG\Info(title="Parkman Assignment", version="0.1")
 */

namespace app\controllers;

use app\models\Garage;
use app\models\MapWrapper;

use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\BadRequestHttpException;


/**
 *
 * @SWG\Model(id="Garage")
 */
class GarageController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     *
     *@SWG\Api(
     *   path="/garage/by-country",
     *   description="Get all garages with in proximity/150km radius, from center of country",
     *@SWG\Operation(
     *      method="GET",
     *      summary="Get all garages with in proximity/150km radius, from center of country"
     *@SWG\Parameter(
     *     name="country",
     *     required=true,
     *     type="string"
     * )
     *@SWG\Parameter(
     *     name="proximit",
     *     required=false,
     *     type="int"
     * )
     *@SWG\ResponseMessage(
     *      code=200,
     *      message='{
     *           "data" : "OBJECT",
     *           "centerLat" : "INT",
     *           "centerLng" => "INT",
     *           "zoom" => "INT"
     *      }'
     * ),
     * @SWG\ResponseMessage(...)
     *   )
     * )
     */
    public function actionByCountry()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $country = \Yii::$app->request->get('country') ? \Yii::$app->request->get('country') : '';
        $proximity = \Yii::$app->request->get('proximity') ? \Yii::$app->request->get('proximity') : 150;

        if(strlen($country) < 1){
            throw new BadRequestHttpException('Invalid country parameter');
        }

        try{
            $mapWrapperObj = new MapWrapper();
            $mapWrapperObj->initMapByCountry($country);

            return $this->_buildResponse($mapWrapperObj, $proximity, MapWrapper::ZOOM_LEVEL_COUNTRY);
        }catch(Exception $e){
            throw new ErrorException("Unable to process request, please try again");
        }

    }

    /**
     *
     *@SWG\Api(
     *   path="/garage/by-geo",
     *   description="Get all garages with in proximity/150km radius, from lat and lng",
     *@SWG\Operation(
     *      method="GET",
     *      summary="Get all garages with in proximity/150km radius, from center of lat and lng"
     *@SWG\Parameter(
     *     name="lat",
     *     required=true,
     *     type="int"
     * )
     *@SWG\Parameter(
     *     name="lng",
     *     required=true,
     *     type="int"
     * )
     *@SWG\Parameter(
     *     name="proximit",
     *     required=false,
     *     type="int"
     * )
     *@SWG\ResponseMessage(
     *      code=200,
     *      message='{
     *           "data" : "OBJECT",
     *           "centerLat" : "INT",
     *           "centerLng" => "INT",
     *           "zoom" => "INT"
     *      }'
     * ),
     * @SWG\ResponseMessage(...)
     *   )
     * )
     */
    public function actionByGeo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $lat = \Yii::$app->request->get('lat') ? \Yii::$app->request->get('lat') : '';
        $lng = \Yii::$app->request->get('lng') ? \Yii::$app->request->get('lng') : '';
        $proximity = \Yii::$app->request->get('proximity') ? \Yii::$app->request->get('proximity') : 150;

        if(strlen($lat) < 1 || strlen($lat) < 1){
            throw new BadRequestHttpException('Invalid or missing Lat/Lng parameter');
        }

        try{
            $mapWrapperObj = new MapWrapper();
            $mapWrapperObj->initMapByLatLng($lat, $lng);

            return $this->_buildResponse($mapWrapperObj, $proximity, MapWrapper::ZOOM_LEVEL_GEOLOC);
        }catch(Exception $e){
            throw new ErrorException("Unable to process request, please try again");
        }

    }

    public function actionByOwner()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $owner = \Yii::$app->request->get('owner') ? \Yii::$app->request->get('owner') : '';
        $proximity = \Yii::$app->request->get('proximity') ? \Yii::$app->request->get('proximity') : 111150;

        if(strlen($owner) < 1){
            throw new BadRequestHttpException('Invalid country parameter');
        }

        try{
            $mapWrapperObj = new MapWrapper();

            $mapWrapperObj->initMapByLatLng(0, 0);

            return $this->_buildResponse($mapWrapperObj, $proximity, MapWrapper::ZOOM_LEVEL_OWNER, $owner);
        }catch(Exception $e){
            throw new ErrorException("Unable to process request, please try again");
        }

    }

    /**
     * @param $mapWrapperObj MapWrapper
     * @param $proximity
     * @param $zoomLevel
     * @access
     * @return array
     */
    private function _buildResponse($mapWrapperObj,$proximity, $zoomLevel, $owner=null)
    {
        $garage = new Garage();

        if(!is_null($owner)){
            $data = $garage->getByOwer(
                $mapWrapperObj->getCenterLat(),
                $mapWrapperObj->getCenterLng(),
                $owner,
                $proximity,
                Garage::DISTANCE_KM
            );
        }else{
            $data = $garage->getByLatLong(
                $mapWrapperObj->getCenterLat(),
                $mapWrapperObj->getCenterLng(),
                $proximity,
                Garage::DISTANCE_KM
            );
        }


        return [
            'data' => $data,
            'centerLat' => $mapWrapperObj->getCenterLat(),
            'centerLng' => $mapWrapperObj->getCenterLng(),
            'zoom' => $zoomLevel
        ];
    }

}

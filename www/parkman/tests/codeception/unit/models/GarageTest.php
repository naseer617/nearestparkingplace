<?php
/**
 * @creator   Naseer Ahmad <naseer@bt.tn>
 * @created  2017-02-08
 * @package
 * @category
 */

namespace app\tests\codeception\unit\models;

use app\models\Garage;
use PHPUnit\Framework\TestCase;

class GarageTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        //$this->loadFixtures(['garages']);
    }

    public function testConstants()
    {
        $this->assertTrue(Garage::DISTANCE_KM == 'km', "Incorrect value of constant DISTANCE_KM.");

        $this->assertTrue(Garage::DISTANCE_MILES == 'miles', "Incorrect value of constant DISTANCE_MILES.");

        $this->assertTrue(Garage::RADIUS_MILES == 3956, "Incorrect value of constant RADIUS_MILES.");

        $this->assertTrue(Garage::RADIUS_KM == 6371, "Incorrect value of constant RADIUS_KM.");
    }

    public function testTableName()
    {
        $this->assertEquals('garages', Garage::tableName(),'Table name should be garages, '.Garage::tableName().' given.');
    }

    public function testGetEarthRadius()
    {
        $garage = new Garage();
        $radius = $this->invokeMethod($garage, '_getEarthRadius', ['km']);

        $this->assertEquals(Garage::RADIUS_KM, $radius);

        $radius = $this->invokeMethod($garage, '_getEarthRadius', ['miles']);

        $this->assertEquals(Garage::RADIUS_MILES, $radius);

        $radius = $this->invokeMethod($garage, '_getEarthRadius', ['kmmm']);

        $this->assertEquals(Garage::RADIUS_MILES, $radius);
    }

    public function testRadiusFormula()
    {
        $lat = 10;
        $lng = 10;
        $radius = 300;

        $expectedFormula = "( ".$radius." * acos( cos( radians(".$lat.") )
              * cos( radians( garages.lat ) )
              * cos( radians( garages.lng ) - radians(".$lng.") )
              + sin( radians(".$lat.") )
              * sin( radians( garages.lat ) ) ) ) AS distance ";

        $garage = new Garage();
        $generatedFormula = $this->invokeMethod($garage, '_getDistanceFormula', [$radius, $lat, $lng]);

        $this->assertEquals($expectedFormula, $generatedFormula);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
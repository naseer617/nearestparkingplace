<?php
/**
 * @creator   Naseer Ahmad <naseer@bt.tn>
 * @created  2017-02-09
 * @package
 * @category
 */

namespace app\tests\codeception\unit\models;


use app\models\MapWrapper;
use PHPUnit\Framework\TestCase;

class MapWrapperTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testConstants()
    {
        $this->assertEquals(8,MapWrapper::ZOOM_LEVEL_COUNTRY);
        $this->assertEquals(10,MapWrapper::ZOOM_LEVEL_GEOLOC);
        $this->assertEquals(2,MapWrapper::ZOOM_LEVEL_OWNER);
        $this->assertEquals("AIzaSyCqHtoANjbEjDsOhiJy6-1D1VBKOhR1Q7k",MapWrapper::GOOGLE_API_KEY);
        $this->assertEquals("https://maps.googleapis.com/maps/api/geocode/json",MapWrapper::GEOCODE_BASE_URL);

    }
}
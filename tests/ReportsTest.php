<?php
namespace Onfido\Tests;

use Onfido\Config;
use Onfido\Report;

class ReportsTest extends AbstractTest
{

    protected static $reports;

    public static function setUpBeforeClass()
    {
        self::$reports = null;
    }

    public static function tearDownAfterClass()
    {
        self::$reports = null;
    }

    public function testListAll()
    {
        Config::init()->set_token($this->getToken())->paginate(null, 5);

        $reports = (new Report())->get('e573d91a-691d-473e-b460-a43c73d3a8ee');
        $this->assertLessThanOrEqual(5, count($reports));

        self::$reports = $reports;
    }

    public function testGet()
    {
        Config::init()->set_token($this->getToken());

        $report = (new Report())->get('e573d91a-691d-473e-b460-a43c73d3a8ee', self::$reports[0]->id);
        $this->assertInstanceOf('stdClass', $report);
        $this->assertObjectHasAttribute('id', $report);
        $this->assertEquals(self::$reports[0]->id, $report->id);
    }

}

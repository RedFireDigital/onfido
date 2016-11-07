<?php
namespace Onfido\Tests;


use Onfido\CheckReport;
use Onfido\Address;
use Onfido\Applicant;
use Onfido\Check;
use Onfido\Config;

class ChecksTest extends AbstractTest
{

    protected static $checks;

    public static function setUpBeforeClass()
    {
        self::$checks = null;
    }

    public static function tearDownAfterClass()
    {
        self::$checks = null;
    }

    public function testListAll()
    {
        Config::init()->set_token($this->getToken())->paginate(null, 5);

        $checks = (new Check())->get('112d8d98-f5d6-478b-bc43-f86ffa2724c8');
        $this->assertLessThanOrEqual(5, count($checks));

        self::$checks = $checks;
    }

    public function testGet()
    {
        Config::init()->set_token($this->getToken());

        $check = (new Check())->get('112d8d98-f5d6-478b-bc43-f86ffa2724c8', self::$checks[0]->id);
        $this->assertInstanceOf('stdClass', $check);
        $this->assertObjectHasAttribute('id', $check);
        $this->assertEquals(self::$checks[0]->id, $check->id);
    }

    public function testCreate()
    {
        Config::init()->set_token($this->getToken());

        $random = time() . rand(0, 999);

        $applicant = new Applicant();
        $applicant->first_name = 'John' . $random;
        $applicant->last_name = 'Smith';
        $applicant->email = 'email' . $random . '@server.com';

        $address1 = new Address();
        $address1->postcode = 'SW4 6EH';
        $address1->town = 'London';
        $address1->country = 'GBR';

        $applicant->addresses = Array($address1);

        $response = $applicant->create();

        $this->assertInstanceOf('stdClass', $response);
        $this->assertObjectHasAttribute('first_name', $response);
        $this->assertEquals($response->first_name, 'John' . $random);

        $check = new Check();
        $check->type = 'standard';

        $report1 = new CheckReport();
        $report1->name = 'identity';

        $check->reports = Array(
            $report1
        );
        $response = $check->create_for($response->id);

        $this->assertInstanceOf('stdClass', $response);
    }

}

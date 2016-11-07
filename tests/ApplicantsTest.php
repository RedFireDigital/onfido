<?php
namespace Onfido\Tests;

use Onfido\Address;
use Onfido\AddressPicker;
use Onfido\Applicant;
use Onfido\Config;

class ApplicantsTest extends AbstractTest
{

    protected static $applicants;

    public static function setUpBeforeClass()
    {
        self::$applicants = null;
    }

    public static function tearDownAfterClass()
    {
        self::$applicants = null;
    }

    public function testListAll()
    {
        Config::init()->set_token($this->getToken())->paginate(null, 5);

        $applicants = (new Applicant())->get();
        $this->assertEquals(5, count($applicants));

        self::$applicants = $applicants;
    }

    public function testGet()
    {
        Config::init()->set_token($this->getToken());

        $applicant = (new Applicant())->get(self::$applicants[0]->id);
        $this->assertInstanceOf('stdClass', $applicant);
        $this->assertObjectHasAttribute('email', $applicant);
        $this->assertEquals(self::$applicants[0]->email, $applicant->email);
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
    }

    public function testAddress()
    {
        Config::init()->set_token($this->getToken())->paginate(null, 10);

        $address = new AddressPicker();
        $address->postcode = 'SW4 6EH';
        $addresses = $address->pick();

        $this->assertGreaterThanOrEqual(1, count($addresses));
        $this->assertInstanceOf('stdClass', $addresses[0]);
        $this->assertObjectHasAttribute('country', $addresses[0]);
        $this->assertEquals($addresses[0]->country, 'GBR');
        $this->assertEquals($addresses[0]->town, 'LONDON');
    }

}

<?php
namespace Onfido\Tests;



use Onfido\Config;
use Onfido\Document;
use Onfido\Applicant;

class DocumentsTest extends AbstractTest
{

    public function testUpload()
    {
        Config::init()->set_token($this->getToken())->paginate(null, 5);

        $applicants = (new Applicant())->get();
        $this->assertEquals(5, count($applicants));

        $imageDir = dirname(__FILE__);
        $imagePath = $imageDir.'/c.jpg';

        $document = new Document();

        $document->file_name = 'c.jpg';
        $document->file_path = $imagePath;
        $document->file_type = 'image/jpg';
        $document->type = 'passport';
        $document->side = 'front';

        $response = $document->upload_for($applicants[0]->id);

        $this->assertInstanceOf('stdClass', $response);
        $this->assertObjectHasAttribute('id', $response);
        $this->assertAttributeNotEmpty('id', $response);
        $this->assertEquals($document->file_name, $response->file_name);
    }

}

<?php
/**
 * Created by Carl Owens (carl@partfire.co.uk)
 * Company: PartFire Ltd (www.partfire.co.uk)
 * Copyright © 2016 PartFire Ltd. All rights reserved.
 *
 * User:    Carl Owens
 * Date:    07/11/2016
 * Time:    10:27
 * File:    AbstractTest.php
 **/

namespace Onfido\Tests;


abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
    protected $token = 'test_NY2rpxJR3C0YwS9WWDzSMoFJ5s95-7aV';

    protected function getToken()
    {
        return $this->token;
    }
}

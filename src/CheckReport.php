<?php
/**
 * Created by Carl Owens (carl@partfire.co.uk)
 * Company: PartFire Ltd (www.partfire.co.uk)
 * Copyright © 2016 PartFire Ltd. All rights reserved.
 *
 * User:    Carl Owens
 * Date:    09/11/2016
 * Time:    06:31
 * File:    CheckReport.php
 **/

namespace Onfido;

class CheckReport
{
    public $name, $variant, $options;

    public function get($check_id, $report_id)
    {
        $response = (new Request('GET', 'checks/' . $check_id . '/reports/' . $report_id ))->send($this);

        return $response;
    }
}

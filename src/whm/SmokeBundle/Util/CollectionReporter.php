<?php
/**
 * Created by PhpStorm.
 * User: langn
 * Date: 02.06.15
 * Time: 21:34
 */

namespace whm\SmokeBundle\Util;

use whm\Smoke\Reporter\Reporter;

class CollectionReporter implements Reporter
{
    private $results;

    public function finish()
    {
    }

    public function process($result)
    {
        $this->results[] = $result;
    }

    public function getResults()
    {
        return $this->results;
    }
}
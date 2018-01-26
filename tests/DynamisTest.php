<?php
/**
 * Created by PhpStorm.
 * User: tpernicano
 * Date: 1/26/18
 * Time: 2:20 PM
 */

namespace Tests;

use Dynamis\Dynamis;
use PHPUnit\Framework\TestCase;

class DynamisTest extends TestCase
{
    public function testConstructWithArray()
    {
        $array = [0, 1, 2];

        $dynamis = Dynamis::createFrom($array);

        foreach ($dynamis as $key => $value) {
            $this->assertEquals($array[$key], $value);
        }
    }
}

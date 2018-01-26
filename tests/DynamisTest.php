<?php
/**
 * Created by PhpStorm.
 * User: tpernicano
 * Date: 1/26/18
 * Time: 2:20 PM
 */

namespace Tests;

use Dynamis\Dynamis;
use Generator;
use PHPUnit\Framework\TestCase;
use Traversable;

class DynamisTest extends TestCase
{
    const ARRAY = [0, 1, 2, 4, 5, 6, 7, 8, 9, 10];
    const CSV = [
        ['id', 'name'  , 'team'],
        [   0, 'Ruby'  , 'RWBY'],
        [   1, 'Weiss' , 'RWBY'],
        [   2, 'Blake' , 'RWBY'],
        [   3, 'Yang'  , 'RWBY'],
        [   4, 'Jaune' , 'JNPR'],
        [   5, 'Nora'  , 'JNPR'],
        [   6, 'Pyrrha', 'JNPR'],
        [   7, 'Ren'   , 'JNPR'],
    ];

    public function testCreateFromArray()
    {

        $dynamis = Dynamis::createFrom(self::ARRAY);

        foreach ($dynamis as $key => $value) {
            $this->assertEquals(self::ARRAY[$key], $value);
        }
    }

    public function testCreateFromGenerator()
    {
        $dynamis = Dynamis::createFrom((function (): Generator {
            $i = 0;

            while (true) {
                yield $i++;
            }
        })());

        foreach ($dynamis as $key => $value) {
            if ($value > 3) {
                break;
            }

            $this->assertEquals($key, $value);
        }
    }

    public function testMap()
    {
        $dynamis = Dynamis::createFrom(self::ARRAY);

        $mapped = $dynamis->map(function (int $value): int {
            return $value * 2;
        });

        foreach ($mapped as $key => $value) {
            $this->assertEquals(self::ARRAY[$key] * 2, $value);
        }
    }

    public function testFilter()
    {
        $dynamis = Dynamis::createFrom(self::ARRAY);

        $filtered = $dynamis->filter(function (int $value): bool {
            return $value % 2 === 0;
        });

        foreach ($filtered as $key => $value) {
            $this->assertEquals(0, $value % 2);
        }
    }

    public function testReduce()
    {
        $dynamis = Dynamis::createFrom(self::ARRAY);

        $add = function (int $a, int $b): int {
            return $a + $b;
        };

        $this->assertEquals(
            array_reduce(self::ARRAY, $add, 0),
            $dynamis->reduce($add, 0));
    }

    public function testMeddle()
    {
        $dynamis = Dynamis::createFrom(self::CSV);

        $data = $dynamis->meddle(function (Traversable $iterator): Generator {
            $keys = null;

            foreach ($iterator as $row) {
                if ($keys === null) {
                    $keys = $row;
                    continue;
                }
                yield array_combine($keys, $row);
            }
        });

        foreach ($data as $key => $row) {
            $this->assertArrayHasKey('id'  , $row);
            $this->assertArrayHasKey('name', $row);
            $this->assertArrayHasKey('team', $row);
            $this->assertEquals($key, $row['id']);
            $this->assertEquals(self::CSV[$key + 1][1], $row['name']);
        }
    }
}

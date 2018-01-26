<?php

namespace Dynamis;

use ArrayObject;
use IteratorAggregate;
use Traversable;

/**
 * Class Dynamis
 * @package Dynamis
 */
class Dynamis implements IteratorAggregate, DynamisInterface
{
    use DynamisTrait;

    /**
     * @var Traversable
     */
    protected $iterator;

    /**
     * Processor constructor.
     * @param iterable $iterator
     */
    public function __construct(iterable $iterator)
    {
        if ($iterator instanceof Traversable) {
            $this->iterator = $iterator;
        }

        $this->iterator = new ArrayObject($iterator);
    }

    /**
     * @param iterable $iterator
     * @return DynamisInterface
     */
    public static function createFrom(iterable $iterator): DynamisInterface
    {
        return new self($iterator);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}

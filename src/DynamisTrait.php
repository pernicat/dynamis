<?php

namespace Dynamis;


use Generator;
use Traversable;

/**
 * Class DynamisTrait
 * @package Dynamis
 * 
 * @mixin DynamisInterface
 * @property Traversable $iterator
 * @method static createFrom(iterable $iterator): DynamisInterface
 */
trait DynamisTrait
{


    /**
     * @param callable $callback
     * @return DynamisInterface
     */
    public function filter(callable $callback): DynamisInterface
    {
        return $this->meddle(function (iterable $iterator) use ($callback): Generator {
            foreach ($iterator as $item) {
                if ($callback($item)) {
                    yield $item;
                }
            }
        });
    }

    /**
     * @param callable $callback
     * @return DynamisInterface
     */
    public function map(callable $callback): DynamisInterface
    {
        return $this->meddle(function (iterable $iterator) use ($callback): Generator {
            foreach ($iterator as $item) {
                yield $callback($item);
            }
        });
    }

    /**
     * @param callable $callback
     * @return DynamisInterface
     */
    public function each(callable $callback): DynamisInterface
    {
        foreach ($this as $item) {
            $callback($item);
        }

        return $this;
    }

    /**
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial)
    {
        foreach ($this as $item) {
            $initial = $callback($initial, $item);
        }

        return $initial;
    }

    /**
     * @param callable $callback
     * @return DynamisInterface
     */
    public function meddle(callable $callback): DynamisInterface
    {
        return self::createFrom($callback($this));
    }



    /**
     * @return Generator
     */
    public function getGenerator(): Generator
    {
        foreach ($this as $item)
        {
            yield $item;
        }
    }

    /**
     * @param int $size
     * @param callable $callback
     * @return void
     */
    public function group(int $size, callable $callback)
    {
        $limiter = function (Generator $generator) use ($size): Generator {
            for ($i = 0; $i < $size; $i++) {
                if (!$generator->valid()) {
                    break;
                }
                yield $generator->current();
                $generator->next();
            }
        };

        $generator = $this->getGenerator();

        $generator->rewind();

        while($generator->valid()) {
            $callback(self::createFrom($limiter($generator)));
        }
    }
}
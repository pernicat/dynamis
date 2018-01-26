<?php

namespace Dynamis;


use Traversable;

/**
 * Interface DynamisInterface
 * @package Dynamis
 */
interface DynamisInterface extends Traversable
{
    /**
     * @param callable $callback
     * @return self
     */
    public function filter(callable $callback): self;

    /**
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback): self;

    /**
     * @param callable $callback
     * @return self
     */
    public function meddle(callable $callback): self;

    /**
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial);

    /**
     * @param callable $callback
     * @return self
     */
    public function each(callable $callback): self;

    /**
     * @param int $size
     * @param callable $callback
     */
    public function group(int $size, callable $callback);
}
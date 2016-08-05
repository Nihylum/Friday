<?php declare(strict_types=1);
/**
 * This source is part of the Nihylum Friday Template Engine.
 *
 * (c) 2016 Matthias Kaschubowski
 *
 * The License is stored at the root of this repository / package.
 */

namespace Nihylum\Friday\Container;


/**
 * Class AggregatorContainer
 *
 * This class organizes all aggregations and their corresponding class names.
 *
 * @package Nihylum\Friday\Container
 */
class AggregatorContainer
{
    /**
     * holds all aggregations.
     *
     * @var
     */
    protected $items;

    /**
     * sets an aggregator to a given class name.
     *
     * @param string $className
     * @param callable $aggregator
     */
    public function set(string $className, callable $aggregator)
    {
        $this->items[$className] = $aggregator;
    }

    /**
     * getter for a class name
     *
     * @param string $className
     * @throws \RuntimeException
     * @return callable
     */
    public function get(string $className): callable
    {
        if ( ! $this->has($className) ) {
            throw new \RuntimeException("Unknown aggregator for class `{$className}`");
        }

        return $this->items[$className];
    }

    /**
     * checks whether a class name is registered to the container or not.
     *
     * @param string $className
     * @return bool
     */
    public function has(string $className): bool
    {
        return array_key_exists($className, $this->items);
    }
}
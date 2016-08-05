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
 * Class FunctionContainer
 *
 * This class organizes all template functions and their corresponding names.
 *
 * @package Nihylum\Friday\Container
 */
class FunctionContainer
{
    /**
     * @var array
     */
    protected $functions = [];

    /**
     * FunctionContainer constructor.
     * @param array $functions
     */
    public function __construct(array $functions = [])
    {
        foreach ( $functions as $name => $function ) {
            $this->set($name, $function);
        }
    }

    /**
     * @param string $name
     * @param callable $callback
     */
    public function set(string $name, callable $callback)
    {
        $this->functions[$this->marshalName($name)] = $callback;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($this->marshalName($name), $this->functions);
    }

    /**
     * @param string $name
     * @return callable
     */
    public function get(string $name): callable
    {
        if ( ! $this->has($name) ) {
            throw new \RuntimeException("Unknown function with name `{$name}`");
        }

        return $this->functions[$this->marshalName($name)];
    }

    /**
     * @param string $name
     * @return string
     */
    protected function marshalName(string $name): string
    {
        return strtolower($name);
    }
}
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
 * Class AssignmentContainer
 *
 * This class organizes all assignments and their corresponding keys.
 *
 * @package Nihylum\Friday\Container
 */
class AssignmentContainer implements \IteratorAggregate
{
    /**
     * holds all assignments
     *
     * @var array
     */
    protected $assignments = [];

    /**
     * Constructor.
     *
     * @param array $assignments
     */
    public function __construct(array $assignments = [])
    {
        foreach ( $assignments as $key => $value ) {
            $this->set($key, $value);
        }
    }

    /**
     * sets a value to its assignment-key.
     *
     * @param string $assignment
     * @param $value
     */
    public function set(string $assignment, $value)
    {
        $this->assignments[$assignment] = $value;
    }

    /**
     * checks whether an assignment-key exists or not.
     *
     * @param string $assignment
     * @return bool
     */
    public function has(string $assignment): bool
    {
        return array_key_exists($assignment, $this->assignments);
    }

    /**
     * gets a value by its assignment-key.
     *
     * @param string $assignment
     * @throws \RuntimeException
     * @return string
     */
    public function get(string $assignment): string
    {
        if ( ! $this->has($assignment) ) {
            throw new \RuntimeException("Unknown item with name `{$assignment}`");
        }

        return $this->assignments[$assignment];
    }


    /**
     * creates a generator from the current assignments.
     *
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        yield from $this->assignments;
    }
}
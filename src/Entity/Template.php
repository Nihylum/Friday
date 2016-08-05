<?php declare(strict_types=1);
/**
 * This source is part of the Nihylum Friday Template Engine.
 *
 * (c) 2016 Matthias Kaschubowski
 *
 * The License is stored at the root of this repository / package.
 */

namespace Nihylum\Friday\Entity;


use Nihylum\Friday\Container\AggregatorContainer;
use Nihylum\Friday\Container\AssignmentContainer;
use Nihylum\Friday\Container\FunctionContainer;
use Nihylum\Friday\Engine;

/**
 * Class Template
 * @package Nihylum\Friday\Entity
 */
class Template
{
    /**
     * @var AssignmentContainer
     */
    protected $assignments;

    /**
     * @var AssignmentContainer
     */
    protected $globals;

    /**
     * @var FunctionContainer
     */
    protected $functions;

    /**
     * @var AggregatorContainer
     */
    protected $aggregations;

    /**
     * @var Engine
     */
    protected $engine;

    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * Constructor.
     *
     * @param \SplFileInfo $file
     * @param Engine $engine
     * @param AssignmentContainer $globals
     * @param FunctionContainer $functions
     * @param AggregatorContainer $aggregations
     */
    public function __construct(\SplFileInfo $file, Engine $engine, AssignmentContainer $globals, FunctionContainer $functions, AggregatorContainer $aggregations)
    {
        $this->engine = $engine;
        $this->globals = $globals;
        $this->functions = $functions;
        $this->aggregations = $aggregations;

        if ( ! $file->isFile() ) {
            throw new \RuntimeException('File target is not a file');
        }

        $this->file = $file;
        $this->assignments = new AssignmentContainer();
    }

    /**
     * assigns a template variable locally.
     *
     * @param string $key
     * @param $value
     */
    public function assign(string $key, $value)
    {
        $this->assignments->set($key, $value);
    }

    /**
     * Assignment Container getter.
     *
     * @return AssignmentContainer
     */
    public function getAssignments(): AssignmentContainer
    {
        return $this->assignments;
    }

    /**
     * assignment container getter for globals.
     *
     * @return AssignmentContainer
     */
    public function getGlobals(): AssignmentContainer
    {
        return $this->globals;
    }

    /**
     * function container getter.
     *
     * @return FunctionContainer
     */
    public function getFunctions(): FunctionContainer
    {
        return $this->functions;
    }

    /**
     * aggregator container getter.
     *
     * @return AggregatorContainer
     */
    public function getAggregations(): AggregatorContainer
    {
        return $this->aggregations;
    }

    /**
     * Engine instance getter.
     *
     * @return Engine
     */
    public function getEngine(): Engine
    {
        return $this->engine;
    }

    /**
     * file handle object getter.
     *
     * @return \SplFileInfo
     */
    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }

    /**
     * render query command.
     *
     * @param AssignmentContainer|null $assignments
     * @return string
     */
    public function render(AssignmentContainer $assignments = null): string
    {
        foreach ( $this->assignments as $key => $value ) {
            if ( ! $assignments->has($key) ) {
                $assignments->set($key, $value);
            }
        }

        $scope = \Closure::bind(function(\SplFileInfo $file) {
            ob_start();
            include $file->getRealPath();
            return ob_get_clean().PHP_EOL;
        }, new Scope($this, $assignments), Scope::class);

        return $scope($this->file);
    }
}
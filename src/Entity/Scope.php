<?php declare(strict_types=1);
/**
 * This source is part of the Nihylum Friday Template Engine.
 *
 * (c) 2016 Matthias Kaschubowski
 *
 * The License is stored at the root of this repository / package.
 */

namespace Nihylum\Friday\Entity;
use Nihylum\Friday\Container\AssignmentContainer;


/**
 * Class Scope
 * @package Nihylum\Friday\Entity
 */
final class Scope
{
    /**
     * @var Template
     */
    protected $template;

    /**
     * @var AssignmentContainer
     */
    protected $assignments;

    /**
     * Scope constructor.
     *
     * @param Template $template
     * @param AssignmentContainer $assignments
     */
    public function __construct(Template $template, AssignmentContainer $assignments)
    {
        $this->template = $template;
        $this->assignments = $assignments;
    }

    /**
     * magic getter for unknown properties.
     *
     * @param string $key
     * @return mixed|string
     */
    public function __get(string $key)
    {
        if ( $this->assignments->has($key) ) {
            $subject = $this->assignments->get($key);
        }
        else if ( $this->template->getGlobals()->has($key) ) {
            $subject = $this->template->getGlobals()->get($key);
        }
        else {
            throw new \RuntimeException("Unknown template variable with name `{$key}`");
        }

        if ( is_object($subject) ) {
            $className = get_class($subject);

            if ( $this->template->getAggregations()->has($className) ) {
                $callback = $this->template->getAggregations()->get($className);

                return call_user_func($callback, $subject);
            }
        }

        return $subject;
    }

    /**
     * magic caller for unknown methods.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        if ( ! $this->template->getFunctions()->has($method) ) {
            throw new \RuntimeException("Unknown template method with name `{$method}`");
        }

        $callback = $this->template->getFunctions()->get($method);

        return call_user_func($callback, $this->template->getEngine(), $this->template, ... $args);
    }
}
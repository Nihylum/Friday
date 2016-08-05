<?php declare(strict_types=1);
/**
 * This source is part of the Nihylum Friday Template Engine.
 *
 * (c) 2016 Matthias Kaschubowski
 *
 * The License is stored at the root of this repository / package.
 */

namespace Nihylum\Friday\Command;


use Nihylum\Friday\Container\AssignmentContainer;
use Nihylum\Friday\Engine;
use Nihylum\Friday\Entity\Template;

/**
 * Class IncludeCommand
 *
 * This class implements the include template function.
 *
 * @package Nihylum\Friday\Command
 */
class IncludeCommand
{
    /**
     * Invoker.
     *
     * @param Engine $engine
     * @param Template $currentTemplate
     * @param $templateName
     * @param array ...$variant
     * @return string
     */
    public function __invoke(Engine $engine, Template $currentTemplate, $templateName, ... $variant): string
    {
        $template = $engine->load($templateName);

        if ( empty($variant) ) {
            return $template->render($currentTemplate->getAssignments());
        }

        $assignments = new AssignmentContainer();
        $this->marshalVariantTo($assignments, $variant, $currentTemplate);

        return $template->render($assignments);
    }

    /**
     * marshals valid variants into the given AssignmentContainer instance.
     *
     * @param AssignmentContainer $assignmentContainer
     * @param array $variant
     * @param Template $currentTemplate
     * @throws \RuntimeException
     */
    protected function marshalVariantTo(AssignmentContainer $assignmentContainer, array $variant, Template $currentTemplate)
    {
        foreach ( $variant as $current ) {
            if ( is_string($current) ) {
                $assignmentContainer->set($current, $currentTemplate->getAssignments()->get($current));
            }
            else if ( is_array($current) ) {
                $this->marshalAssignmentsTo($assignmentContainer, $current);
            }
            else {
                throw new \RuntimeException(
                    sprintf(
                        'Incompatible inbound variant of type {%s} for template inclusion',
                        gettype($current)
                    )
                );
            }
        }
    }

    /**
     * marshals assignments into the given AssignmentContainer instance.
     *
     * @param AssignmentContainer $assignmentContainer
     * @param array $assignments
     * @throws \RuntimeException
     */
    protected function marshalAssignmentsTo(AssignmentContainer $assignmentContainer, array $assignments)
    {
        foreach ( $assignments as $key => $value ) {
            if ( ! is_string($key) || is_numeric($key) ) {
                throw new \RuntimeException("incompatible assignment-key: {$key}");
            }

            $assignmentContainer->set($key, $value);
        }
    }
}
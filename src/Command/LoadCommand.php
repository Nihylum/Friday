<?php declare(strict_types=1);
/**
 * This source is part of the Nihylum Friday Template Engine.
 *
 * (c) 2016 Matthias Kaschubowski
 *
 * The License is stored at the root of this repository / package.
 */

namespace Nihylum\Friday\Command;


use Nihylum\Friday\Engine;
use Nihylum\Friday\Entity\Template;

/**
 * Class LoadCommand
 *
 * This class implements the load template function
 *
 * @package Nihylum\Friday\Command
 */
class LoadCommand
{
    /**
     * Invoker.
     *
     * @param Engine $engine
     * @param Template $currentTemplate
     * @param string $templateName
     * @return Template
     */
    public function __invoke(Engine $engine, Template $currentTemplate, string $templateName): Template
    {
        return $engine->load($templateName);
    }
}
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
 * Class FormatCommand
 *
 * This class implements the format template function.
 *
 * @package Nihylum\Friday\Command
 */
class FormatCommand
{
    /**
     * Invoker.
     *
     * @param Engine $engine
     * @param Template $currentTemplate
     * @param string $format
     * @param array ...$params
     * @return string
     */
    public function __invoke(Engine $engine, Template $currentTemplate, string $format, ... $params): string
    {
        return sprintf($format, ... $params);
    }
}
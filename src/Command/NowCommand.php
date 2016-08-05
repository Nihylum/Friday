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
 * Class NowCommand
 *
 * This class implements the Now template function.
 *
 * @package Nihylum\Friday\Command
 */
class NowCommand
{
    /**
     * Invoker.
     *
     * @param Engine $engine
     * @param Template $currentTemplate
     * @param string $timezone
     * @return \DateTimeInterface
     */
    public function __invoke(Engine $engine, Template $currentTemplate, string $timezone = 'UTC'): \DateTimeInterface
    {
        return date_create('now', new \DateTimeZone($timezone));
    }
}
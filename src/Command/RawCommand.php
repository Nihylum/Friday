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

class RawCommand
{
    public function __invoke(Engine $engine, Template $currentTemplate, string $name)
    {
        return $currentTemplate->getAssignments()->get($name);
    }
}
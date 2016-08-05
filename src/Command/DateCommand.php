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
 * Class DateCommand
 *
 * This class implements the date formatting template function.
 *
 * @package Nihylum\Friday\Command
 */
class DateCommand
{
    /**
     * Invoker.
     *
     * @param Engine $engine
     * @param Template $currentTemplate
     * @param $variant
     * @param string $format
     * @return string
     */
    public function __invoke(Engine $engine, Template $currentTemplate, $variant, string $format = 'Y-m-d H:i:s')
    {
        if ( $variant instanceof \DateTimeInterface ) {
            return $variant->format($format);
        }

        if ( is_string($variant) ) {
            return date_create($variant)->format($format);
        }

        if ( is_integer($variant) ) {
            return date_create_from_format('U', $variant)->format($format);
        }

        if ( is_float($variant) ) {
            return date_create_from_format('U.u', $variant)->format($format);
        }

        throw new \RuntimeException('Incompatible type or instance given to date template function');
    }
}
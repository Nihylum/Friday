<?php declare(strict_types=1);
/**
 * This source is part of the Nihylum Friday Template Engine.
 *
 * (c) 2016 Matthias Kaschubowski
 *
 * The License is stored at the root of this repository / package.
 */

namespace Nihylum\Friday;


use Nihylum\Friday\Container\DirectoryHandlerContainer;

/**
 * Interface DirectoryResolverInterface
 *
 * defines the interface for a directory resolver.
 *
 * @package Nihylum\Friday
 */
interface DirectoryResolverInterface
{
    /**
     * resolves a query to its SplFileInfo instance.
     *
     * @param DirectoryHandlerContainer $directories
     * @param \SplFileInfo $fallback
     * @param string $query
     * @param string[] ...$extensions
     * @return \SplFileInfo
     */
    public function resolve(DirectoryHandlerContainer $directories, \SplFileInfo $fallback, string $query, string ... $extensions): \SplFileInfo;
}
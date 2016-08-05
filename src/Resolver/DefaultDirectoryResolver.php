<?php declare(strict_types=1);
/**
 * This source is part of the Nihylum Friday Template Engine.
 *
 * (c) 2016 Matthias Kaschubowski
 *
 * The License is stored at the root of this repository / package.
 */

namespace Nihylum\Friday\Resolver;


use Nihylum\Friday\Container\DirectoryHandlerContainer;
use Nihylum\Friday\DirectoryResolverInterface;

/**
 * Class DefaultDirectoryResolver
 *
 * Implements the default Directory Resolver.
 *
 * @package Nihylum\Friday\Resolver
 */
class DefaultDirectoryResolver implements DirectoryResolverInterface
{
    protected $prefix = '@';
    protected $suffix = ':';

    /**
     * resolves a query to its SplFileInfo instance.
     *
     * @param DirectoryHandlerContainer $directories
     * @param \SplFileInfo $fallback
     * @param string $query
     * @param string[] ...$extensions
     * @return \SplFileInfo
     */
    public function resolve(DirectoryHandlerContainer $directories, \SplFileInfo $fallback, string $query, string ... $extensions): \SplFileInfo
    {
        $pattern = $directories->hasAliases()
            ? $this->forgeRegexPattern(... $directories->getAliases())
            : sprintf('~^%s(?<alias>(.*))%s(?<path>(.*))~iu', $this->prefix, $this->suffix)
        ;

        if ( 0 < preg_match($pattern, $query, $matches) ) {
            $directoryHandle = $directories->hasAliases() && $directories->has($matches['alias'])
                ? $directories->get($matches['alias'])
                : $fallback
            ;

            $query = $matches['path'];
        }
        else {
            $directoryHandle = $fallback;
        }

        foreach ( $extensions as $current ) {
            $filePath = $this->forgeFilePath($directoryHandle->getRealPath(), basename($query), $current);

            if ( file_exists($filePath) ) {
                return new \SplFileInfo($filePath);
            }
        }

        throw new \RuntimeException("Unable to resolve template path for `{$query}`");
    }


    /**
     * @param string[] ...$aliases
     * @return string
     */
    protected function forgeRegexPattern(string ... $aliases): string
    {
        $quotedAliases = array_map('preg_quote', $aliases);

        return sprintf(
            '~^%s(?<alias>(%s))%s(?<path>(.*))~iu',
            preg_quote($this->prefix, '~'),
            join('|', $quotedAliases),
            preg_quote($this->suffix, '~')
        );
    }

    /**
     * forges the file path by the given basePath, baseName and extension.
     *
     * @param string $basePath
     * @param string $baseName
     * @param string $extension
     * @return string
     */
    protected function forgeFilePath(string $basePath, string $baseName, string $extension): string
    {
        return sprintf('%s/%s.%s', $basePath, $baseName, ltrim($extension, '.'));
    }

}
<?php declare(strict_types=1);
/**
 * This source is part of the Nihylum Friday Template Engine.
 *
 * (c) 2016 Matthias Kaschubowski
 *
 * The License is stored at the root of this repository / package.
 */

namespace Nihylum\Friday\Container;


/**
 * Class DirectoryHandlerContainer
 *
 * This class organizes all directory handles and their corresponding aliases.
 *
 * @package nihylum.friday
 */
class DirectoryHandlerContainer
{
    /**
     * holds all directory handlers
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * @param string $alias
     * @param \SplFileInfo $handle
     */
    public function set(string $alias, \SplFileInfo $handle)
    {
        $this->handlers[$this->marshalAlias($alias)] = $this->marshalHandle($handle);
    }

    /**
     * checks whether an alias exists or not.
     *
     * @param string $alias
     * @return bool
     */
    public function has(string $alias): bool
    {
        return array_key_exists($this->marshalAlias($alias), $this->handlers);
    }

    /**
     * getter for aliases.
     *
     * @param string $alias
     * @throws \RuntimeException
     * @return \SplFileInfo
     */
    public function get(string $alias): \SplFileInfo
    {
        if ( ! $this->has($alias) ) {
            throw new \RuntimeException("alias <{$alias}> is not known");
        }

        return $this->handlers[$this->marshalAlias($alias)];
    }

    /**
     * Returns an array of all known aliases.
     *
     * @return string[]
     */
    public function getAliases(): array
    {
        return array_keys($this->handlers);
    }

    /**
     * Checks whether aliases are registered or not.
     *
     * @return bool
     */
    public function hasAliases(): bool
    {
        return ! empty($this->handlers);
    }

    /**
     * marshals the handler instance for a given path.
     *
     * @param string $path
     * @return \SplFileInfo
     */
    public static function marshalHandlerInstance(string $path): \SplFileInfo
    {
        return new \SplFileInfo($path);
    }

    /**
     * marshals the alias-key for a given alias.
     *
     * @param string $alias
     * @return string
     */
    protected function marshalAlias(string $alias): string
    {
        return strtolower($alias);
    }

    /**
     * marshals the integrity for a given handler.
     *
     * @param \SplFileInfo $handler
     * @throws \RuntimeException
     * @return \SplFileInfo
     */
    protected function marshalHandle(\SplFileInfo $handler): \SplFileInfo
    {
        if ( ! $handler->isDir() ) {
            throw new \RuntimeException("target is not a directory: {$handler->getRealPath()}");
        }

        return $handler;
    }
}
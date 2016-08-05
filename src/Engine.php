<?php declare(strict_types=1);
/**
 * This source is part of the Nihylum Friday Template Engine.
 *
 * (c) 2016 Matthias Kaschubowski
 *
 * The License is stored at the root of this repository / package.
 */

namespace Nihylum\Friday;



use Nihylum\Friday\Command\DateCommand;
use Nihylum\Friday\Command\FormatCommand;
use Nihylum\Friday\Command\IncludeCommand;
use Nihylum\Friday\Command\LoadCommand;
use Nihylum\Friday\Command\NowCommand;
use Nihylum\Friday\Command\RawCommand;
use Nihylum\Friday\Container\AggregatorContainer;
use Nihylum\Friday\Container\AssignmentContainer;
use Nihylum\Friday\Container\DirectoryHandlerContainer;
use Nihylum\Friday\Container\FunctionContainer;
use Nihylum\Friday\Entity\Template;
use Nihylum\Friday\Resolver\DefaultDirectoryResolver;

/**
 * Class Engine
 * @package Nihylum\Friday
 */
class Engine
{
    /**
     * @var DirectoryHandlerContainer
     */
    protected $directories;

    /**
     * @var AggregatorContainer
     */
    protected $aggregations;

    /**
     * @var FunctionContainer
     */
    protected $functions;

    /**
     * @var \SplFileInfo
     */
    protected $defaultHandle;

    /**
     * @var DefaultDirectoryResolver
     */
    protected $directoryResolver;

    /**
     * @var array|\string[]
     */
    protected $extensions = [];

    /**
     * @var AssignmentContainer
     */
    protected $globals;

    /**
     * Constructor.
     *
     * @param string $templateDirectory
     * @param \string[] ...$extensions
     */
    public function __construct(string $templateDirectory, string ... $extensions)
    {
        $defaultHandle = DirectoryHandlerContainer::marshalHandlerInstance($templateDirectory);

        if ( ! $defaultHandle->isDir() ) {
            throw new \RuntimeException('template directory target is not a directory.');
        }

        $this->defaultHandle = $defaultHandle;
        $this->extensions = ! empty($extensions) ? $extensions : ['html', 'tpl'];

        $this->directories = new DirectoryHandlerContainer();
        $this->aggregations = new AggregatorContainer();
        $this->functions = new FunctionContainer($this->marshalDefaultTemplateFunctions());
        $this->directoryResolver = new DefaultDirectoryResolver();
        $this->globals = new AssignmentContainer();
    }

    /**
     * assigns a template variable globally.
     *
     * @param string $key
     * @param $value
     */
    public function assign(string $key, $value)
    {
        $this->globals->set($key, $value);
    }

    /**
     * assigns template variables from array globally.
     *
     * @param array $assignments
     */
    public function assignFromArray(array $assignments)
    {
        foreach ( $assignments as $key => $value ) {
            $this->globals->set($key, $value);
        }
    }

    /**
     * mounts a directory to a handler-alias.
     *
     * @param string $handler
     * @param string $directory
     */
    public function mount(string $handler, string $directory)
    {
        $this->directories->set($handler, DirectoryHandlerContainer::marshalHandlerInstance($directory));
    }

    /**
     * renders a given template by its name and an optionally provided assignments array.
     *
     * @param string $templateName
     * @param array $assignments
     * @return string
     */
    public function render(string $templateName, array $assignments = []): string
    {
        $template = $this->load($templateName);
        return $template->render(new AssignmentContainer($assignments));
    }

    /**
     * sets the directory resolver implementation.
     *
     * @param DirectoryResolverInterface $resolver
     */
    public function setDirectoryResolver(DirectoryResolverInterface $resolver)
    {
        $this->directoryResolver = $resolver;
    }

    /**
     * sets a template function by the given name and callback.
     *
     * @param string $name
     * @param callable $callback
     */
    public function setFunction(string $name, callable $callback)
    {
        $this->functions->set($name, $callback);
    }

    /**
     * sets a value aggregator by the given class name and callback.
     *
     * @param string $className
     * @param callable $callback
     */
    public function setAggregator(string $className, callable $callback)
    {
        $this->aggregations->set($className, $callback);
    }

    /**
     * creates a Template-class instances by the given template name and an optionally to the extension stack
     * attached extension.
     *
     * @param string $templateName
     * @param string[] ...$extensions
     * @return Template
     */
    public function load(string $templateName, string ... $extensions): Template
    {
        $extensions = array_unique(
            array_filter($this->extensions + $extensions)
        );

        $file = $this->directoryResolver->resolve(
            $this->directories,
            $this->defaultHandle,
            $templateName,
            ... $extensions
        );

        return new Template($file, $this, $this->globals, $this->functions, $this->aggregations);
    }

    /**
     * internal function to marshal the default template functions.
     *
     * This function may be overwritten (instead of flashing new instances to the constructur), to
     * apply additional template functions to a modified Engine-class.
     *
     * @return array
     */
    protected function marshalDefaultTemplateFunctions(): array
    {
        return [
            'load' => new LoadCommand(),
            'include' => new IncludeCommand(),
            'format' => new FormatCommand(),
            'date' => new DateCommand(),
            'now' => new NowCommand(),
            'raw' => new RawCommand(),
        ];
    }
}
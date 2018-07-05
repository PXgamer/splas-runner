<?php

namespace pxgamer\SplasRunner;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 */
class Application extends BaseApplication
{
    const NAME = 'Splas Runner';
    const VERSION = '@git-version@';

    /**
     * Application constructor.
     *
     * @param null $name
     * @param null $version
     */
    public function __construct($name = null, $version = null)
    {
        parent::__construct(
            $name ?: static::NAME,
            $version ?: (static::VERSION === '@'.'git-version@' ? 'source' : static::VERSION)
        );
    }

    /**
     * @return array|\Symfony\Component\Console\Command\Command[]
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        $commands[] = new RunCommand();

        return $commands;
    }
}

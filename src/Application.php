<?php

namespace pxgamer\SplasRunner;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    const NAME = 'Splas Runner';
    const VERSION = '@git-version@';

    public function __construct($name = null, $version = null)
    {
        parent::__construct(
            $name ?: static::NAME,
            $version ?: (static::VERSION === '@' . 'git-version@' ? 'source' : static::VERSION)
        );
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        $commands[] = new RunCommand();

        return $commands;
    }
}

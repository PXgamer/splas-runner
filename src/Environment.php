<?php

namespace pxgamer\SplasRunner;

/**
 * Class Environment
 */
class Environment
{
    const OS_UNKNOWN = 0;
    const OS_OSX = 1;
    const OS_WINDOWS = 2;
    const OS_LINUX = 3;

    const ERROR_NO_HOME_DIRECTORY = 'No home directory set in environment.';

    /**
     * @return string
     *
     * @throws \ErrorException
     */
    public static function getHomeDirectory()
    {
        $os = self::getOS();

        switch ($os) {
            case self::OS_WINDOWS:
                if (array_key_exists('HOMEDRIVE', $_SERVER) && array_key_exists('HOMEPATH', $_SERVER)) {
                    return $_SERVER['HOMEDRIVE'].$_SERVER['HOMEPATH'];
                }
                break;
            case self::OS_OSX:
            case self::OS_LINUX:
                if (array_key_exists('HOME', $_SERVER)) {
                    return $_SERVER['HOME'];
                }
                break;
            default:
                throw new \ErrorException(self::ERROR_NO_HOME_DIRECTORY);
        }
    }

    /**
     * @return string
     *
     * @throws \ErrorException
     */
    public static function getSplasRunnerDirectory()
    {
        $homeDirectory = self::getHomeDirectory();

        return $homeDirectory.DIRECTORY_SEPARATOR.'.splasr'.DIRECTORY_SEPARATOR;
    }

    /**
     * @return int
     */
    static public function getOS()
    {
        switch (true) {
            case stristr(PHP_OS, 'DAR'):
                return self::OS_OSX;
            case stristr(PHP_OS, 'WIN'):
                return self::OS_WINDOWS;
            case stristr(PHP_OS, 'LINUX'):
                return self::OS_LINUX;
            default :
                return self::OS_UNKNOWN;
        }
    }
}
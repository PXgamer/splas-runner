<?php

namespace pxgamer\SplasRunner;

use pxgamer\Splas\Splas;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunCommand
 * @package pxgamer\SplasRunner
 */
class RunCommand extends Command
{
    const ERROR_DOWNLOADING_IMAGE = 'Error downloading the image.';
    const ERROR_UNSUPPORTED_OS = 'This operating system is not supported.';

    /**
     * The path to the backgrounds directory
     *
     * @var string
     */
    private $backgroundDirectory;
    /**
     * An Unsplash API key
     *
     * @var string|null
     */
    private $apiKey;
    /**
     * The interval to wait in minutes, by default the script is executed once.
     *
     * @var int|null
     */
    private $interval;
    /**
     * Whether to keep the images or not
     *
     * @var bool
     */
    private $keepImages = false;
    /**
     * For using the output instance in other functions
     *
     * @var Output
     */
    private $output;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Run the Splas Runner.')
            ->addOption(
                'interval',
                'i',
                InputOption::VALUE_REQUIRED,
                'The required interval. Defaults to execute once then close.'
            )
            ->addOption(
                'keep',
                'k',
                InputOption::VALUE_NONE,
                'Whether to keep all downloaded images. Defaults to remove.'
            )
            ->addOption(
                'key',
                null,
                InputOption::VALUE_REQUIRED,
                'Your Unsplash API key. Defaults to use the `UNSPLASH_API_KEY` environment variable if not provided.'
            );
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     * @throws \ErrorException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->apiKey = $input->getOption('key') ?? getenv('UNSPLASH_API_KEY');

        if (!$this->apiKey) {
            throw new \ErrorException('Unspecified API key.');
        }

        $this->keepImages = $input->getOption('keep');
        $this->interval = (int)$input->getOption('interval');

        $this->getBackgroundsDirectory();

        $output->writeln([
            $this->getApplication()->getName() . ' <info>' . $this->getApplication()->getVersion() . '</info>',
            '',
            'Photos from https://unsplash.com',
            '',
        ]);

        $output->writeln([
            '<info>Press Ctrl+C to exit at any time.</info>',
            '',
        ]);

        $this->output = $output;

        $this->interval > 0 ? $this->runOnInterval() : $this->runOnce();
    }

    /**
     * Run the download only once
     */
    private function runOnce()
    {
        $this->clearBackgroundsDirectory();

        $this->downloadImage();
    }

    /**
     * Run the download on a loop
     */
    private function runOnInterval()
    {
        set_time_limit(0);

        while (true) {
            $this->clearBackgroundsDirectory();

            $this->downloadImage();

            sleep($this->interval * 60); // Sleep for number of minutes
        }
    }

    /**
     * Download the actual image file and then change the background
     * @throws \ErrorException
     */
    private function downloadImage()
    {
        $client = new Splas($this->apiKey);

        $selectedImage = $client->getRandom();

        $rawUrl = $selectedImage['urls']['raw'] ?? false;

        if ($rawUrl) {
            $fileName = $selectedImage['id'];
            $outputDirectory = $this->backgroundDirectory . $fileName . ".jpg";

            $this->output->writeln([
                '<comment>' . $fileName . '</comment>'
                . ' by <comment>' . $selectedImage['user']['name']
                . ' (' . $selectedImage['user']['links']['html'] . ')</comment>',
            ]);

            $ch = curl_init();
            $fp = fopen($outputDirectory, 'wb');

            curl_setopt_array(
                $ch,
                [
                    CURLOPT_URL    => $rawUrl,
                    CURLOPT_FILE   => $fp,
                    CURLOPT_HEADER => 0,
                ]
            );

            curl_exec($ch);

            // Close connections
            curl_close($ch);
            fclose($fp);

            $this->changeWallpaper($outputDirectory);
        } else {
            $this->output->writeln([
                '<error>' . self::ERROR_DOWNLOADING_IMAGE . '</error>',
            ]);
        }
    }

    /**
     * Set the wallpaper for supported operating systems, otherwise throw ErrorException.
     *
     * @param string $imagePath
     * @return bool
     * @throws \ErrorException
     */
    private function changeWallpaper($imagePath)
    {
        if (stristr(PHP_OS, 'DAR')) {
            // Mac not supported yet
        }

        if (stristr(PHP_OS, 'WIN')) {
            // Run Windows executable to change background
            exec('reg add "HKEY_CURRENT_USER\Control Panel\Desktop" /v Wallpaper /t REG_SZ /d "'.$imagePath.'" /f');
            exec('rundll32.exe user32.dll,UpdatePerUserSystemParameters');

            return true;
        }

        if (stristr(PHP_OS, 'LINUX')) {
            // Attempt to change background for Linux (via GSettings)
            exec('gsettings set org.gnome.desktop.background picture-uri "file://' . $imagePath . '"');

            return true;
        }

        throw new \ErrorException(self::ERROR_UNSUPPORTED_OS);
    }

    /**
     * Set the relative background directory
     *
     * @return string
     */
    private function getBackgroundsDirectory()
    {
        $this->backgroundDirectory = __DIR__ . '/../resources/backgrounds/';

        return $this->backgroundDirectory;
    }

    /**
     * Clear the backgrounds directory if 'keep' option isn't specified
     *
     * @return bool
     */
    private function clearBackgroundsDirectory()
    {
        if (!$this->keepImages) {
            $iterator = new \DirectoryIterator($this->backgroundDirectory);
            foreach ($iterator as $file) {
                if ($file->isDot() || $file->isDot()) {
                    continue;
                }
                unlink($this->backgroundDirectory . $file->current());
            }
        }

        return true;
    }
}

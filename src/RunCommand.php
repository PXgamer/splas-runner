<?php

namespace pxgamer\SplasRunner;

use GuzzleHttp\Client;
use pxgamer\Splas\Splas;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\PathUtil\Path;

/**
 * Class RunCommand
 */
class RunCommand extends Command
{
    const ERROR_DOWNLOADING_IMAGE = 'Error downloading the image.';
    const ERROR_UNSUPPORTED_OS = 'This operating system is not supported.';
    const SPLASR_DIRECTORY = '.splasr';

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
    protected function configure(): void
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
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->apiKey = $input->getOption('key') ?? getenv('UNSPLASH_API_KEY');

        if (!$this->apiKey) {
            throw new \ErrorException('Unspecified API key.');
        }

        $this->keepImages = $input->getOption('keep');
        $this->interval = (int)$input->getOption('interval');

        $this->getBackgroundsDirectory();

        $output->writeln([
            $this->getApplication()->getName().' <info>'.$this->getApplication()->getVersion().'</info>',
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
     * Run the download only once.
     * @return void
     * @throws \ErrorException
     */
    private function runOnce(): void
    {
        $this->clearBackgroundsDirectory();

        $this->downloadImage();
    }

    /**
     * Run the download on a loop.
     * @return void
     * @throws \ErrorException
     */
    private function runOnInterval(): void
    {
        set_time_limit(0);

        while (true) {
            $this->clearBackgroundsDirectory();

            $this->downloadImage();

            // Sleep for the number of minutes
            sleep($this->interval * 60);
        }
    }

    /**
     * Download the actual image file and then change the background.
     * @return void
     * @throws \ErrorException
     */
    private function downloadImage(): void
    {
        $client = new Splas($this->apiKey);

        $selectedImage = $client->getRandom();

        $rawUrl = $selectedImage['urls']['raw'] ?? false;

        if ($rawUrl) {
            $fileName = $selectedImage['id'];
            $outputPath = $this->backgroundDirectory.DIRECTORY_SEPARATOR.$fileName.".jpg";

            $this->output->writeln([
                '<comment>'.$fileName.'</comment>'
                .' by <comment>'.$selectedImage['user']['name']
                .' ('.$selectedImage['user']['links']['html'].')</comment>',
            ]);

            $guzzleClient = new Client();
            $guzzleClient->get(
                $rawUrl,
                [
                    'sink' => $outputPath,
                ]
            );

            if (file_exists($outputPath)) {
                $this->changeWallpaper($outputPath);

                return;
            }
        }

        $this->output->writeln([
            '<error>'.self::ERROR_DOWNLOADING_IMAGE.'</error>',
        ]);
    }

    /**
     * Set the wallpaper for supported operating systems, otherwise throw ErrorException.
     *
     * @param string $imagePath
     * @return bool
     * @throws \ErrorException
     */
    private function changeWallpaper(string $imagePath): bool
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
            exec('gsettings set org.gnome.desktop.background picture-uri "file://'.$imagePath.'"');

            return true;
        }

        throw new \ErrorException(self::ERROR_UNSUPPORTED_OS);
    }

    /**
     * Set the relative background directory.
     *
     * @return string
     * @throws \Exception
     */
    private function getBackgroundsDirectory(): string
    {
        $this->backgroundDirectory = Path::getHomeDirectory().DIRECTORY_SEPARATOR.self::SPLASR_DIRECTORY;

        if (!is_dir($this->backgroundDirectory)) {
            mkdir($this->backgroundDirectory);
        }

        return $this->backgroundDirectory;
    }

    /**
     * Clear the backgrounds directory if 'keep' option isn't specified
     *
     * @return void
     */
    private function clearBackgroundsDirectory(): void
    {
        if (!$this->keepImages) {
            $iterator = new \DirectoryIterator($this->backgroundDirectory);
            foreach ($iterator as $file) {
                if ($file->isDot() || $file->isDot() || $file->getExtension() !== 'jpg') {
                    continue;
                }
                unlink($this->backgroundDirectory.DIRECTORY_SEPARATOR.$file->current());
            }
        }
    }
}

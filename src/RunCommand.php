<?php

namespace pxgamer\SplasRunner;

use pxgamer\splas;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    const ERROR_DOWNLOADING_IMAGE = 'Error downloading the image.';

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
            ->addOption('interval', 'i', InputOption::VALUE_REQUIRED,
                'The required interval. Defaults to execute once then close.')
            ->addOption('keep', 'k', InputOption::VALUE_NONE,
                'Whether to keep all downloaded images. Defaults to remove.')
            ->addOption('key', null, InputOption::VALUE_REQUIRED,
                'Whether to keep all downloaded images. Defaults to remove.');
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
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
            '<comment>Press Ctrl+C to exit at any time.</comment>',
            ''
        ]);

        $this->output = $output;

        $this->interval > 0 ? $this->runOnInterval() : $this->runOnce();
    }

    private function runOnce()
    {
        $this->clearBackgroundsDirectory();

        $this->downloadImage();
    }

    private function runOnInterval()
    {
        set_time_limit(0);

        while (true) {
            $this->clearBackgroundsDirectory();

            $this->downloadImage();

            sleep($this->interval * 60); // Sleep for number of minutes
        }
    }

    private function downloadImage()
    {
        $client = new splas($this->apiKey);

        $selectedImage = json_decode($client->getRandom());

        $rawUrl = $selectedImage->urls->raw ?? false;

        if ($rawUrl) {
            $fileName = uniqid('bg-') . ".jpg";
            $outputDirectory = $this->backgroundDirectory . $fileName;

            $this->output->writeln([
                '<info>Grabbing image: ' . $fileName . '.</info>'
            ]);

            $ch = curl_init();
            $fp = fopen($outputDirectory, 'wb');

            curl_setopt_array(
                $ch,
                [
                    CURLOPT_URL => $rawUrl,
                    CURLOPT_FILE => $fp,
                    CURLOPT_HEADER => 0,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ]
            );

            curl_exec($ch);

            // Close connections
            curl_close($ch);
            fclose($fp);

            $this->changeWallpaper($outputDirectory);
        } else {
            $this->output->writeln([
                '<error>' . self::ERROR_DOWNLOADING_IMAGE . '</error>'
            ]);
        }
    }

    private function changeWallpaper($imagePath)
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            exec(__DIR__ . '/../resources/bin/wallpaper ' . $imagePath);
        }
    }

    private function getBackgroundsDirectory()
    {
        $this->backgroundDirectory = __DIR__ . '/../resources/backgrounds/';

        return $this->backgroundDirectory;
    }

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
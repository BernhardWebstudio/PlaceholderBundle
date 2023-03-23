<?php

namespace BernhardWebstudio\PlaceholderBundle\Commands;

use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderProviderService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class PlaceholderPrepareCommand extends Command
{

    protected $provider;

    public function __construct(PlaceholderProviderService $provider = null)
    {
        $this->provider = $provider;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('bewe:placeholder:prepare')
            ->addOption('dry')
            ->addOption('ignore-mtime')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates placeholders for all the images.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command creates the placeholders for all the images in your configured load_paths');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->provider === null) {
            throw new Exception("The Placeholder Provider Service was not automatically injected. You have probably misconfigured the DI-container.");
        }

        $dry = $input->getOption('dry');
        if ($dry) {
            $output->writeln("Dry run. No images will be generated.");
        }
        $finder = new Finder();
        $finder->name("/\.jpe?g$/")->name('*.png');
        foreach ($this->provider->getLoadPaths() as $path) {
            $finder->in($path);
        }

        if (count($this->provider->getLoadPaths()) > 0) {

            $finder->files();
            // loop the images to be generated inside
            foreach ($finder as $image) {
                $inputPath = $image->getRealPath();
                $outputPath = $this->provider->getOutputPath($inputPath);
                // only output if not already done in another session
                // TODO: accept ignore_mtime in parameters too
                if (
                    !\file_exists($outputPath)
                    || (!$input->getOption('ignore-mtime') && filemtime($inputPath) > filemtime($outputPath))
                ) {
                    if (!$dry) {
                        // do output images
                        try {
                            $reason = $this->determineDumpReason($inputPath, $outputPath);
                            $path = $this->provider->getPlaceholder(
                                $inputPath,
                                PlaceholderProviderService::MODE_PATH
                            );
                            // be verbose and output reason for dump
                            $output->writeln(sprintf('%s created from %s because %s.', $path, $inputPath, $reason));
                            assert($path === $outputPath);
                        } catch (\Exception $e) {
                            $output->writeln($path . ' failed to be created. Error message: "' . $e->getMessage() . '".');
                        }
                    } else {
                        // do output image path before and after
                        $output->writeln($inputPath . ' would have been processed to ' . $outputPath . '.');
                    }
                }
            }
            $output->writeln('Processed ' . count($finder) . ' images.');
        } else {
            $output->writeln('Processed ' . 0 . ' images.');
        }

        return 0;
    }

    /**
     * Determine why an image will be created
     *
     * @param string $inputPath
     * @param string $outputPath
     * @return string
     */
    protected function determineDumpReason($inputPath, $outputPath)
    {
        if (!\file_exists($outputPath)) {
            return 'output did not yet exist';
        }
        if (($inputDate = filemtime($inputPath)) > ($outputDate = filemtime($outputPath))) {
            return 'input was newer: ' . date('d.m.Y h:i:s', $inputDate) . ' vs. ' . date('d.m.Y h:i:s', $outputDate);
        }
        return 'unknown reason';
    }
}

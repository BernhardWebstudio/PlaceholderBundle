<?php

namespace BernhardWebstudio\PlaceholderBundle\Commands;

use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderProviderService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class PlaceholderPrepareCommand extends Command
{

    protected $provider;

    public function __construct(PlaceholderProviderService $provider)
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

        // the short description shown while running "php bin/console list"
            ->setDescription('Creates placeholders for all the images.')

        // the full command description shown when running the command with
        // the "--help" option
            ->setHelp('This command creates the placeholders for all the images in your configured load_paths');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dry = $input->getOption('dry');
        if ($dry) {
            $output->writeln("Dry run. No images will be generated.");
        }
        $finder = new Finder();
        $finder->name("/\.jpe?g$/")->name('*.png');
        foreach ($this->provider->getLoadPaths() as $path) {
            $finder->in($path);
        }

        $finder->files();
        // loop the images to be generated inside
        foreach ($finder as $image) {
            $inputPath = $image->getRealPath();
            $outputPath = $this->provider->getOutputPath($inputPath);
            // only output if not already done in another session
            if (!\file_exists($outputPath) || (filemtime($inputPath) > filemtime($outputPath))) {
                if (!$dry) {
                    // do output images
                    try {
                        $path = $this->provider->getPlaceholder(
                            $inputPath,
                            PlaceholderProviderService::MODE_PATH
                        );
                        $output->writeln($path . ' created.');
                    } catch (\Exception $e) {
                        $output->writeln($path . ' failed to be created.');
                    }
                } else {
                    // do output image path before and after
                    $output->writeln($inputPath . ' would have been processed to ' . $outputPath . '.');
                }
            }
        }

        $output->writeln("Processed " . count($finder) . ' images.');
    }
}

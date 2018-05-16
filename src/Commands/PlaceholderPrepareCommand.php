<?php 

namespace BernhardWebstudio\PlaceholderBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderProviderService;

class PlaceholderPrepareCommand extends Command {

    protected $provider;

    public function __counstruct(PlaceholderProviderService $provider) {
        $this->provider = $provider;
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
        ->setHelp('This command creates the placeholders for all the images in your load_paths');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dry = $input->getOption('dry');
        $finder = new Finder();
        $finder->name("/\.jpe?g$/")->name('*.png');
        foreach ($this->provider->getLoadPaths() as $path) {
            $finder->in($path);
        }

        $finder->files();
        $output = array();
        if (!$dry) {
            foreach ($finder as $image) {
                $path = $this->provider->getPlaceholder($image->getRealPath(), PlaceholderProviderService::MODE_PATH);
                $output->writeln($path . ' created.');
            }
        } else {
            $output->writeln("Dry run. No images will be generated.");
        }

        $output->writeln("Handled " . count($finder) . ' images.');
    }
}
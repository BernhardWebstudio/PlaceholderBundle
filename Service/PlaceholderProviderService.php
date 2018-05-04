<?php

namespace BernhardWebstudio\PlaceholderBundle\Service;

use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderGeneratorInterface;
use Psr\Log\LoggerInterface;

class PlaceholderProviderService
{
    protected $generator;
    protected $logger;

    public function __construct(PlaceholderGeneratorInterface $generator, LoggerInterface $logger = null)
    {
        $this->generator = $generator;
        $this->logger = $logger;
    }

    public function getPlaceholder($inputfile)
    {
        $outputfile = $this->getOutputPath($inputfile);
        if (!\file_exists($outputfile) || filemtime($inputfile) < filemtime($outputfile)) {
            $this->generator->generate($inputfile, $outputfile);
        }
        return $outputfile;
    }

    /**
     * Get the actual path to a placeholder
     */
    protected function getOutputPath(string $filename)
    {
        $extension_pos = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($filename, 0, $extension_pos) . '_thumb' . substr($filename, $extension_pos);
        // let the service add a custom extension
        $files = glob("$thumb*");
        return count($files) === 1 ? $files[0] : $thumb;
    }
}

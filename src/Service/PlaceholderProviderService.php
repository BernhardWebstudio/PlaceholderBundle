<?php

namespace BernhardWebstudio\PlaceholderBundle\Service;

use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderGeneratorInterface;
use Psr\Log\LoggerInterface;

class PlaceholderProviderService
{
    protected $generator;
    protected $logger;
    protected $loadPaths;

    public function __construct(
        PlaceholderGeneratorInterface $generator,
        array $loadPaths = array(),
        LoggerInterface $logger = null
    ) {
        $this->generator = $generator;
        $this->loadPaths = $loadPaths;
        $this->logger = $logger;
    }

    public function getPlaceholder($inputfile)
    {
        // resolve input path
        $inputfile = $this->getInputPath($inputfile);
        $outputfile = $this->getOutputPath($inputfile);
        if (!\file_exists($outputfile) || filemtime($inputfile) < filemtime($outputfile)) {
            $this->generator->generate($inputfile, $outputfile);
        }
        return $outputfile;
    }

    /**
     * Get the actual path to a generated placeholder
     */
    protected function getOutputPath(string $filename)
    {
        $extension_pos = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($filename, 0, $extension_pos) . '_thumb' . substr($filename, $extension_pos);
        // let the service add a custom extension
        return $thumb . $this->generator->getOutputExtension();
    }

    /**
     * Get the actual path to an image
     */
    public function getInputPath(string $filename)
    {
        // test out the possible paths
        $index = 0;
        $testPath = $filename;
        while (!\file_exists($testPath) && $index < count($this->loadPaths)) {
            $testPath = $this->loadPaths[$index] . $filename;
            $index++;
        }
        return \file_exists($testPath) ? $testPath : null;
    }
}

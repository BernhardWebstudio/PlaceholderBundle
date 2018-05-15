<?php

namespace BernhardWebstudio\PlaceholderBundle\Service;

use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderGeneratorInterface;

class PlaceholderProviderService
{
    protected $generator;
    protected $loadPaths;
    protected $outputPath;

    const MODE_RAW = 'raw';
    const MODE_BASE_64 = 'base64';
    const MODE_URL = 'url';
    const MODE_PATH = 'path';

    public function __construct(
        PlaceholderGeneratorInterface $generator,
        array $loadPaths = array(),
        $outputPath = null
    ) {
        $this->generator = $generator;
        $this->loadPaths = $loadPaths;
        $this->outputPath = $outputPath;
    }

    public function getPlaceholder($inputfile, $mode = '')
    {
        // resolve input path
        $inputfile = $this->getInputPath($inputfile);
        if (!$inputfile) {
            return;
        }
        $outputfile = $this->getOutputPath($inputfile);
        if (!\file_exists($outputfile) || filemtime($inputfile) > filemtime($outputfile)) {
            $this->generator->generate($inputfile, $outputfile);
        }

        switch ($mode) {
            case self::MODE_BASE_64:
                return \base64_encode(\file_get_contents($outputfile));
                break;
            // alternative: serve the path to the controller instead.
            // This way, the time used to serve can be reduced
            case self::MODE_RAW:
                return \file_get_contents($outputfile);
                break;
            case self::MODE_URL:
                $url = "data:" . $this->getOutputMime() . ";utf8,";
                if ($this->getOutputMime() === "image/svg+xml") {
                    $url .= \file_get_contents($outputfile);
                } else {
                    $url .= \base64_encode(\file_get_contents($outputfile));
                }
                return $url;
            case self::MODE_PATH:
            default:
                return $outputfile;
                break;
        }
    }

    /**
     * Get the actual path to a generated placeholder
     */
    public function getOutputPath(string $filename)
    {
        if ($this->outputPath) {
            $dir = $this->outputPath;
        } else {
            $dir = \dirname($filename);
        }
        return $dir . $filename;
    }

    /**
     * Get the filename of an outputed placeholder
     */
    protected function getOutputFileName($filename) {
        $extension_pos = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($filename, 0, $extension_pos) . '_thumb' . substr($filename, $extension_pos);
        // let the service add a custom extension
        return $thumb . $this->generator->getOutputExtension();
    }

    /**
     * Get the mimetype of a placeholder. Used e.g. in Conroller to return a suitable Response
     */
    public function getOutputMime()
    {
        return $this->generator->getOutputMime();
    }

    /**
     * Get the actual path to an image
     */
    public function getInputPath(string $filename)
    {
        // test out the possible paths
        // probably, it could be a good idea to use the Symfony Finder component
        $index = 0;
        $testPath = $filename;
        while (!\file_exists($testPath) && $index < count($this->loadPaths)) {
            $testPath = $this->loadPaths[$index] . $filename;
            $index++;
        }
        return \file_exists($testPath) ? $testPath : null;
    }
}

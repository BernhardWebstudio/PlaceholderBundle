<?php

namespace BernhardWebstudio\PlaceholderBundle\Service;

use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderGeneratorInterface;

/**
 * PlaceholderProviderService is an abstraction layer/handler around the
 * PlaceholderGenerator to implement few types of placeholder returns
 */
class PlaceholderProviderService
{
    protected $generator;
    protected $loadPaths;
    protected $outputPath;

    /**
     * The modes how a placeholder can be fetched
     */
    const MODE_RAW = 'raw';
    const MODE_BASE_64 = 'base64';
    const MODE_URL = 'url';
    const MODE_PATH = 'path';

    public function __construct(
        PlaceholderGeneratorInterface $generator,
        array $loadPaths = array(),
        string $outputPath = null
    ) {
        $this->generator = $generator;
        $this->loadPaths = $loadPaths;
        $this->outputPath = $outputPath;
    }

    /**
     * Get the placeholder as a certain mode
     *
     * @param string $inputfile the image to get the placeholder of
     * @param string $mode the mode/kind of placeholder to be returned
     *
     * @return string|null the placeholder as $mode.
     *                  Null if inputfile does not exist in the configured loadPaths
     */
    public function getPlaceholder(string $inputfile, string $mode = '')
    {
        // resolve input path
        $inputfile = $this->getInputPath($inputfile);
        if (!$inputfile) {
            return;
        }
        $outputfile = $this->getOutputPath($inputfile);
        if (!\file_exists($outputfile) || filemtime($inputfile) > filemtime($outputfile)) {
            // the following line may throw exceptions. do they have to be catched?
            // if so: what to do with the error?
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
                    $url .= $this->svgUrlEncode($outputfile);
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
     *
     * @param string $filename the path of the file to get the outputpath of
     */
    public function getOutputPath(string $filename)
    {
        if ($this->outputPath) {
            // hash to make sure the file does not collide with a file with the same name
            return $this->outputPath . "/" . sha1($filename) . $this->generator->getOutputExtension();
        }
        return \dirname($filename) . "/" . $this->getOutputFileName(\basename($filename));
    }

    /**
     * Get the filename of an outputed placeholder
     *
     * @param string $filename the file to get the name of the output of
     * @return string
     */
    protected function getOutputFileName(string $filename)
    {
        $extension_pos = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($filename, 0, $extension_pos) . '_thumb' . substr($filename, $extension_pos);
        // let the service add a custom extension
        return $thumb . $this->generator->getOutputExtension();
    }

    /**
     * Get the mimetype of a placeholder. Used e.g. in Conroller to return a suitable Response
     *
     * @return string
     */
    public function getOutputMime()
    {
        return $this->generator->getOutputMime();
    }

    /**
     * Get the actual path to an image
     *
     * @param string $filename the name of the file to get the inputpath of
     * @return string|null the path to the requested image
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

    /**
     * Use these load paths e.g. for:
     * - dump command
     * - testing
     *
     * @return array
     */
    public function getLoadPaths()
    {
        return $this->loadPaths;
    }

    /**
     * URL-Encode SVGs in a rather compressive way.
     *
     * Inspiration: https://github.com/tigt/mini-svg-data-uri
     *
     * @param string $svgPath path to the svg to encode
     * @return string url encoded svg
     */
    protected function svgUrlEncode(string $svgPath)
    {
        $data = \file_get_contents($svgPath);
        $data = \preg_replace('/\v(?:[\v\h]+)/', ' ', $data);
        $data = \str_replace('"', "'", $data);
        $data = \rawurlencode($data);
        // re-decode a few characters understood by browsers to improve compression
        $data = \str_replace('%20', ' ', $data);
        $data = \str_replace('%3D', '=', $data);
        $data = \str_replace('%3A', ':', $data);
        $data = \str_replace('%2F', '/', $data);
        return $data;
    }
}

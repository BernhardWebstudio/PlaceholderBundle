<?php

namespace BernhardWebstudio\PlaceholderBundle\Service;

/**
 * Interface of Classes generating Placeholders
 */
interface PlaceholderGeneratorInterface
{

    /**
     * Generate the placeholder
     *
     * @param String $inputpath path to file which shall be placeholdered
     * @param String $outputpath path to file|placeholder which shall be generated
     * @return void
     */
    public function generate($inputpath, $outputpath);

    /**
     * Get the extension of the generated Placeholder
     *
     * @return String
     */
    public function getOutputExtension();

    /**
     * Get the mime type of the generated placeholder
     *
     * @return void
     */
    public function getOutputMime();
}

<?php

namespace BernhardWebstudio\PlaceholderBundle\Service;

interface PlaceholderGeneratorInterface
{

    public function generate($inputpath, $outputpath);

    public function getOutputExtension();

    public function getOutputMime();
}

<?php

class PlaceholderProviderService {

    protected $generator;

    public function getPlaceholder($inputfile, $outputfile) {
        if (!\file_exists($outputfile) || filemtime($inputfile) < filemtime($outputfile)) {
            $this->generator->generate($inputfile, $outputfile);
        }
    }
}
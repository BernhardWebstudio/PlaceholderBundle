<?php 

namespace BernhardWebstudio\PlaceholderBundle\Service;

use Symfony\Component\Process\Process;

class SqipPlaceholderGenerator implements PlaceholderGeneratorInterface {

    // TODO: accept parameter
    protected $bin = "sqip";
    protected $iterations = 5;

    public function generate($input, $output) {
        // maybe, the image should be resized before being processed
        $process = new Process(array($this->bin, '-n', $this->iterations, '-o', $output, $input));
        $process->mustRun();
        return $process->getOutput();
    }
}
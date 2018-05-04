<?php 

namespace BernhardWebstudio\PlaceholderBundle\Service;

use Symfony\Component\Process\Process;

class SqipPlaceholderGenerator extends AbstractNodeExecGenerator {

    protected $bin = "sqip";
    protected $iterations = 5;

    public function __construct($bin = "sqip", $iterations = 5)
    {
        $this->bin = $bin;
        $this->iterations = $iterations;
    }

    public function generate($input, $output) {
        // maybe, the image should be resized before being processed
        $process = new Process(array($this->bin, '-n', $this->iterations, '-o', "$output.svg", $input));
        $process->mustRun();
        return $process->getOutput();
    }
}
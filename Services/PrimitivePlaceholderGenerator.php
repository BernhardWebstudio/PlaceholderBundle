<?php 

namespace BernhardWebstudio\PlaceholderBundle\Service;

use Symfony\Component\Process\Process;

class PrimitivePlaceholderGenerator implements PlaceholderGeneratorInterface {

    // TODO: accept parameter
    protected $bin = "primitive";
    protected $iterations = 10;

    public function generate($input, $output) {
        $process = new Process(array($this->bin, '-i', $input, '-o', $output, '-n', $this->iterations));
        $process->mustRun();
        return $process->getOutput();
    }
}
<?php

namespace BernhardWebstudio\PlaceholderBundle\Service;

use Symfony\Component\Process\Process;

class PrimitivePlaceholderGenerator extends AbstractNodeExecGenerator
{

    protected $bin = "primitive";
    protected $iterations = 10;

    public function __construct($bin = "primitive", $iterations = 10)
    {
        $this->bin = $bin;
        $this->iterations = $iterations;
    }

    /**
     * Run primitive to generate the placeholder svg
     */
    public function generate($input, $output)
    {
        $process = new Process(array($this->bin, '-i', $input, '-o', $output, '-n', $this->iterations));
        $process->mustRun();
        return $process->getOutput();
    }
}

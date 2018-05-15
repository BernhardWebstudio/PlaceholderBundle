<?php

namespace BernhardWebstudio\PlaceholderBundle\Service;

use Symfony\Component\Process\Process;

class PrimitivePlaceholderGenerator extends AbstractNodeExecGenerator
{

    protected $bin = "primitive";
    // TODO: use
    protected $node_bin = "node";
    protected $iterations = 10;

    public function __construct($bin = "primitive", $node_bin = "node", $iterations = 10)
    {
        $this->bin = $bin;
        $this->node_bin = $node_bin;
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

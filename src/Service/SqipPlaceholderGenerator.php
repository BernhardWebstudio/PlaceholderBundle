<?php

namespace BernhardWebstudio\PlaceholderBundle\Service;

use Symfony\Component\Process\Process;

class SqipPlaceholderGenerator extends AbstractNodeExecGenerator
{

    protected $bin = "sqip";
    protected $node_bin = "node";
    protected $iterations = 5;

    public function __construct($bin = "sqip", $node_bin = "node", $iterations = 5)
    {
        $this->bin = $bin;
        $this->node_bin = $node_bin;
        $this->iterations = $iterations;
    }

    /**
     * Run sqip to generate the placeholder image svg
     */
    public function generate($input, $output)
    {
        $process = new Process(array($this->node_bin, $this->bin, '-n', $this->iterations, '-o', $output, $input));
        $process->mustRun();
        return $process->getOutput();
    }
}

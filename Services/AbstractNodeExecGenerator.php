<?php 

namespace BernhardWebstudio\PlaceholderBundle\Service;

/**
 * Abstract Generator for all NodeJS related placeholders
 */
abstract class AbstractNodeExecGenerator {
    abstract public function __construct($bin, $iterations);
}
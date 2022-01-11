<?php

namespace BernhardWebstudio\PlaceholderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use BernhardWebstudio\PlaceholderBundle\DependencyInjection\BernhardWebstudioPlaceholderExtension;

class BernhardWebstudioPlaceholderBundle extends Bundle
{
    public function getContainerExtension(): ?\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new BernhardWebstudioPlaceholderExtension();
    }
}

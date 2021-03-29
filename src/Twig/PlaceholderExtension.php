<?php

namespace BernhardWebstudio\PlaceholderBundle\Twig;

use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderProviderService;

class PlaceholderExtension extends \Twig\Extension\AbstractExtension
{
    protected $placeholderProvider;

    public function __construct(PlaceholderProviderService $provider)
    {
        $this->placeholderProvider = $provider;
    }

    public function getFilters()
    {
        return array(
            new \Twig\TwigFilter('placeholder', array($this, 'getPlaceholder'))
        );
    }

    public function getPlaceholder($inputPath, $mode = '')
    {
        return $this->placeholderProvider->getPlaceholder($inputPath, $mode);
    }
}

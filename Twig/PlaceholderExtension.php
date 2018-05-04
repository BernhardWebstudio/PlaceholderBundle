<?php 

namespace BernhardWebstudio\PlaceholderBundle\Twig;

use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderProviderService;

class PlaceholderExtension extends \Twig_Extension {

    const MODE_SVG = 'svg';
    const MODE_BASE_64 = 'base64';

    protected $placeholderProvider;

    public function __construct(PlaceholderProviderService $provider) {
        $this->placeholderProvider = $provider;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('placeholder', array($this, 'getPlaceholder'))
        );
    }

    public function getPlaceholder($inputPath, $mode = 'svg') {
        $placeholderPath = $this->placeholderProvider->getPlaceholder($inputPath);
        switch ($mode) {
            case self::MODE_BASE_64:
                return \base64_encode(\file_get_contents($placeholderPath));
            break;
            // alternative: serve the path to the controller instead. 
            // This way, the time used to serve can be reduced
            case self::MODE_SVG:
            default:
                return $placeholderPath;
            break;
        }
    }

}
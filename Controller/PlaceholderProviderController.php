<?php

namespace BernhardWebstudio\PlaceholderBundle\Controller;

use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderProviderService;

/**
 * @Route(name="bewe_")
 */
class PlaceholderProviderController extends Controller
{
    /**
     * General route to get the placeholder of an image
     * Returns the image if existing, otherwise generates it
     *
     * @Route("/{imagePath}/placeholder", name="placeholder", requirements={"imagePath"=".*"})
     */
    public function placeholderAction(Request $request, string $imagePath)
    {
        /**
         * @var PlaceholderProviderService
         */
        $providerService = $this->get('bewe_placeholder.provider');

        if (!($input = $providerService->getInputPath($imagePath))) {
            throw $this->createNotFoundException();
        }

        $placeholderPath = $providerService->getPlaceholder($input);

        return $this->file($placeholderPath);
    }

    
}

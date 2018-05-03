<?php

namespace BernhardWebstudio\PlaceholderBundle\Controller;

use PlaceholderProviderService;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        if (!\file_exists($imagePath)) {
            throw $this->createNotFoundException();
        }

        /**
         * @var PlaceholderProviderService
         */
        $providerService = $this->get('bewe_placeholder.provider');

        $placeholderPath = $providerService->getPlaceholder($imagePath);

        return $this->file($placeholderPath);
    }

    
}

<?php

namespace BernhardWebstudio\PlaceholderBundle\Controller;

use PlaceholderProviderService;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/{imagePath}?placeholder=true", name="placeholder", requirements={"imagePath"="*"})
     */
    public function placeholderAction(Request $request, PlaceholderProviderService $providerService, string $imagePath)
    {
        $placeholderPath = $providerService->getPlaceholder($imagePath);

        return $this->file($placeholderPath);
    }

    
}

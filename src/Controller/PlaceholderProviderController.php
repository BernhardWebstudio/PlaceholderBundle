<?php

namespace BernhardWebstudio\PlaceholderBundle\Controller;

use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
    public function placeholderAction(string $imagePath)
    {
        //$imagePath = substr($imagePath, 0, -12); // substr /placeholder
        /**
         * @var PlaceholderProviderService
         */
        $providerService = $this->get('bewe_placeholder.provider');

        if (!($input = $providerService->getInputPath($imagePath))) {
            throw $this->createNotFoundException();
        }

        $placeholderPath = $providerService->getPlaceholder($input);

        $response = new Response(\file_get_contents($placeholderPath), Response::HTTP_OK);
        $response->headers->set('Content-Type', $providerService->getOutputMime());
        $response->headers->set('Content-Disposition', ResponseHeaderBag::DISPOSITION_INLINE);
        return $response;
    }
}

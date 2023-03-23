<?php

namespace BernhardWebstudio\PlaceholderBundle\Controller;

use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderProviderService;

/**
 * @Route(name="bewe_")
 */
class PlaceholderProviderController extends AbstractController
{

    /**
     * @var PlaceholderProviderService
     * @required
     */
    private $placeholderProvider;

    public function __construct(PlaceholderProviderService $placeholderProvider)
    {
        $this->placeholderProvider = $placeholderProvider;
    }

    /**
     * General route to get the placeholder of an image
     * Returns the image if existing, otherwise generates it
     *
     * @Route("/{imagePath}/placeholder", name="placeholder", requirements={"imagePath"=".*"})
     */
    #[Route('/{imagePath}/placeholder', name: 'placeholder', requirements: ["imagePath" => ".*"])]
    public function placeholderAction(string $imagePath)
    {
        if (!($input = $this->placeholderProvider->getInputPath($imagePath))) {
            // throw $this->createNotFoundException("This image does not exist.");
            $response = new Response("This image does not exist.", Response::HTTP_NOT_FOUND);
            $response->headers->set('Content-Type', 'text/plain');
            return $response;
        }

        $placeholderPath = $this->placeholderProvider->getPlaceholder($input);

        $response = new Response(\file_get_contents($placeholderPath), Response::HTTP_OK);
        $response->headers->set('Content-Type', $this->placeholderProvider->getOutputMime());
        $response->headers->set('Content-Disposition', ResponseHeaderBag::DISPOSITION_INLINE);
        return $response;
    }
}

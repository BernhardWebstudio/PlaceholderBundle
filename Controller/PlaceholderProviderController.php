<?php

namespace BernhardWebstudio\PlaceholderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;

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
    public function placeholderAction(Request $request, Packages $assetManager, string $imagePath)
    {
        $placeholderPath = $this->getOutputPath($imagePath);
        if (!\file_exists($placeholderPath)) {
            $placeholderService = $this->get();
            $placeholderService->generate($imagePath, $placeholderPath);
        }

        return $this->file($placeholderPath);
    }

    /**
     * Get the actual path to a placeholder
     */
    protected function getOutputPath(string $filename)
    {
        $extension_pos = strrpos($filename, '.'); // find position of the last dot, so where the extension starts
        $thumb = substr($filename, 0, $extension_pos) . '_thumb' . substr($filename, $extension_pos);
        return $thumb;
    }
}

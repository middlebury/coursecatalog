<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * A controller for application path redirects.
 */
class Redirect extends AbstractController
{
    public function __construct(
        private AssetMapperInterface $assetMapper,
    ) {
    }

    /**
     * Redirect blind requests to /favicon.ico to our asset path.
     *
     * The HTML should be generating correct links to our hashed asset URLs,
     * but some clients will blindly request the /favicon.ico path without
     * looking at the HTML, so provide a fallback.
     */
    #[Route('/favicon.ico', name: 'favicon')]
    public function favicon()
    {
        return $this->redirect($this->assetMapper->getPublicPath('favicon.ico'));
    }
}

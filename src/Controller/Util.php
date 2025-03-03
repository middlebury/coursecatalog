<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Util extends AbstractController
{
    /**
     * List Admin Screens.
     */
    #[Route('/clearcache', name: 'clearcache')]
    public function clearcacheAction(Request $request)
    {
        $clearCacheKey = $this->getParameter('app.clear_cache_key');
        if (empty($clearCacheKey) || !strlen(trim($clearCacheKey))) {
            throw new \Exception('app.clear_cache_key parameter is not configured.');
        }
        if ($request->get('key') != $clearCacheKey) {
            throw new \InvalidArgumentException('key supplied does not match app.clear_cache_key');
        }

        apcu_clear_cache();

        $response = new Response('APCu Cache Cleared.');
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }
}

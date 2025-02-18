<?php

namespace App\Controller;

use OneLogin\Saml2\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\FirewallMapInterface;

class Login extends AbstractController
{
    public function __construct(
        private readonly FirewallMapInterface $firewallMap,
    ) {
    }

    #[Route('/login', name: 'login')]
    public function __invoke(Request $request, Auth $auth): RedirectResponse
    {
        if ($request->get('returnTo')) {
            $returnTo = $request->get('returnTo');
            // Ensure that returnTo is the same as our host.
            // This could be more thorough checking to ensure that the URL
            // is below the application's base URL or take into account
            // other routing details, but this seems like a basic sanity check.
            if (!preg_match('#^/.*$#', $returnTo) && $request->getHttpHost() != parse_url($returnTo, PHP_URL_HOST)) {
                throw new \InvalidArgumentException('returnTo must match the current host');
            }
            $targetPath = $returnTo;
        } else {
            $targetPath = '/';
        }

        return new RedirectResponse($this->processLoginAndGetRedirectUrl($auth, $targetPath));
    }

    private function processLoginAndGetRedirectUrl(Auth $auth, ?string $targetPath): string
    {
        $redirectUrl = $auth->login(returnTo: $targetPath, stay: true);
        if (null === $redirectUrl) {
            throw new \RuntimeException('Login cannot be performed: Auth did not returned redirect url.');
        }

        return $redirectUrl;
    }
}

<?php

namespace App\Controller;

use Nbgrp\OneloginSamlBundle\Controller\Login as NbgrpLogin;
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
            if ($request->getHttpHost() != parse_url($returnTo, PHP_URL_HOST)) {
                throw new \InvalidArgumentException('returnTo must match the current host');
            }
            // Set the session variable that the Nbgrp/OneloginSamlBundle is
            // looking for.
            $firewallName = $this->firewallMap->getFirewallConfig($request)?->getName();
            if (!$firewallName) {
                throw new ServiceUnavailableHttpException(message: 'Unknown firewall.');
            }
            $request->getSession()->set('_security.'.$firewallName.'.target_path', $returnTo);
        }
        // Execute the Nbgrp/OneloginSamlBundle's login action.
        $nbgrpLogin = new NbgrpLogin($this->firewallMap);

        return $nbgrpLogin($request, $auth);
    }
}

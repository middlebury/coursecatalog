<?php

namespace App\Controller;

use App\Security\SamlUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Masquerade extends AbstractController
{
    #[Route('/admin/masquerade', name: 'masquerade')]
    public function masqueradeForm()
    {
        $user = $this->getUser();
        $data = [
            'userId' => $user->getUserIdentifier(),
            'userName' => $user->getName(),
        ];

        return $this->render('admin/masquerade.html.twig', $data);
    }

    #[Route('/admin/masquerade/switch', name: 'masquerade_switch', methods: ['POST'])]
    public function masqueradeSwitch(Request $request)
    {
        // Check that the current user is authorized to impersonate others
        $this->denyAccessUnlessGranted('ROLE_ALLOWED_TO_SWITCH');

        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('masquerade-switch', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        // Create the user with their ID and some dummy values.
        // See: https://medium.com/actived/impersonating-users-in-symfony-methods-and-best-practices-63cd80777c4d
        //
        // This could be improved to allow lookups from AzureAD and provide
        // actual properties.
        $user = new SamlUser($request->get('masquerade_user_id'));
        $user->setSamlAttributes([
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' => ['unknown@example.com'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname' => ['Unknown'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname' => ['User'],
            'AssignedRoles' => [],
        ]);

        // Get the security token storage
        $tokenStorage = $this->container->get('security.token_storage');

        // Get the original token
        $originalToken = $tokenStorage->getToken();

        if (!$request->getSession()->get('_switch_user')) {
            $request->getSession()->set('_switch_user', serialize($originalToken));
        }

        // Impersonate the requested user
        $impersonationToken = new UsernamePasswordToken(
            $user,
            'main',
            $user->getRoles()
        );

        // Check if the impersonation is successful
        if ($impersonationToken->getUser() === $user) {
            $redirect = $this->redirect($this->generateUrl('home'));
            $tokenStorage->setToken($impersonationToken);

            return $redirect;
        } else {
            throw new \Exception('Failed to impersonate user');
        }
    }

    #[Route('/masquerade/exit', name: 'masquerade_exit')]
    public function masqueradeExit(Request $request)
    {
        // Get the security token storage
        $tokenStorage = $this->container->get('security.token_storage');

        // Get the original token
        if ($request->getSession()->get('_switch_user')) {
            $originalToken = unserialize($request->getSession()->get('_switch_user'));
            // unset the original token from the session
            $request->getSession()->remove('_switch_user');
            $tokenStorage->setToken($originalToken);

            return $this->redirect($this->generateUrl('home'));
        }

        throw new \Exception('You are not impersonating any user.');
    }
}

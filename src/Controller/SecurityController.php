<?php

namespace App\Controller;

use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private const PROVIDER_KEY = "main";
    /**
     * @Route("/security_login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authChecker, LoginFormAuthenticator $formAuthenticator, Request $request, UserProviderInterface $provider, GuardAuthenticatorHandler $guardAuthenticatorHandler): Response
    {
        // check if we're already authenticated
        if($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('user_index');
        }

        /**
         * We try to connect with credentials given by the user
         */

        if($request->getMethod() == "POST")
        {
            $credentials = $formAuthenticator->getCredentials($request);
            $user = $formAuthenticator->getUser($credentials, $provider);

            if($formAuthenticator->checkCredentials($credentials, $user))
            {
                $formAuthenticator->createAuthenticatedToken($user, $this::PROVIDER_KEY);

                return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $formAuthenticator,
                    $this::PROVIDER_KEY
                );

            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

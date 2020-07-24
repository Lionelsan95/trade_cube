<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use FOS\UserBundle\Event\GetResponseUserEvent;
use \FOS\UserBundle\FOSUserEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $formFactory;
    private $userManager;
    private $dispatcher;
    private $authenticationUtils;
    private $authChecker;
    private $security;
    private $tokenManager;

    public function __construct($formFactory, $userManager, $dispatcher, $authenticationUtils,AuthorizationCheckerInterface $authChecker, Security $security,CsrfTokenManagerInterface $tokenManager=null)
    {
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->dispatcher = $dispatcher;
        $this->authenticationUtils = $authenticationUtils;
        $this->authChecker = $authChecker;
        $this->security = $security;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {

        $wallets = array();

        if($this->authChecker->isGranted('ROLE_ADMIN')) {

            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();

            return $this->render('user/index.html.twig', [
                'users' => $users,
                'wallets'=>$wallets
            ]);
        }else if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')){

            $user = $this->security->getUser();
            $i=0;
            foreach($user->getWallets() as $wallet){
                $wallets[] = [
                    "num"=>(++$i),
                    'id'=>$wallet->getId(),
                    'crypto'=>$wallet->getCryptomonnaie()->getName(),
                    'nom'=>$wallet->getNom(),
                    'platform'=>$wallet->getBlockchain()->getNom(),
                ];
            }

            return $this->render('user/index.html.twig',[
                'wallets'=>$wallets
            ]);
        }else
            return $this->redirectToRoute('user_login');

    }

    /**
     * @Route("/login", name="user_login", methods={"GET"})
     * @param Request $request
     */
    public function loginAction(Request $request){
        if($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('user_index');
        }

        $csrfToken = $this->tokenManager
            ? $this->tokenManager->getToken('authenticate')->getValue()
            : null;

        return $this->render('user/login.html.twig', array(
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'error'         => $this->authenticationUtils->getLastAuthenticationError(),
            'csrf_token' =>$csrfToken
        ));
    }

    /**
     * @Route("/registration", name="user_registration", methods={"GET"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function registerAction(Request $request)
    {
        $user = $this->userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $this->dispatcher->dispatch(FOSUserEvets::REGISTRATION_SUCCESS, $event);

                $this->userManager->updateUser($user);

                /*****************************************************
                 * Add new functionality (e.g. log the registration) *
                 *****************************************************/
                $this->container->get('logger')->info(
                    sprintf("New user registration: %s", $user)
                );

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $event = new FormEvent($form, $request);
            $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}

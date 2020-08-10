<?php

namespace App\Controller;

use App\Entity\UserChain;
use App\Form\UserChainType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/user/chain_bis")
 */
class UserChainController_bis extends AbstractController
{
    private $security;
    private $authChecker;

    public function __construct(Security $security, AuthorizationCheckerInterface $authChecker)
    {
        $this->security = $security;
        $this->authChecker = $authChecker;
    }
    /**
     * @Route("/", name="user_chain_index", methods={"GET"})
     */
    public function index(): Response
    {
        $userChains = $this->getDoctrine()
            ->getRepository(UserChain::class)
            ->findAll();

        return $this->render('user_chain/index.html.twig', [
            'user_chains' => $userChains,
        ]);
    }

    /**
     * @Route("/new", name="user_chain_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $userChain = new UserChain();
        $form = $this->createForm(UserChainType::class, $userChain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userChain);
            $entityManager->flush();

            return $this->redirectToRoute('view_blockchain', [
                'id'=>$userChain->getBlockchain()->getId()
            ]);
        }

        return $this->render('user_chain/new.html.twig', [
            'user_chain' => $userChain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_chain_show", methods={"GET"})
     */
    public function show(UserChain $userChain): Response
    {
        return $this->render('user_chain/show.html.twig', [
            'user_chain' => $userChain,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_chain_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserChain $userChain): Response
    {
        $form = $this->createForm(UserChainType::class, $userChain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_chain_index');
        }

        return $this->render('user_chain/edit.html.twig', [
            'user_chain' => $userChain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_chain_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserChain $userChain): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userChain->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userChain);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_chain_index');
    }
}

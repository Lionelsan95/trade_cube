<?php

namespace App\Controller;

use App\Entity\Cryptomonnaie;
use App\Form\CryptomonnaieType;
use App\Repository\CryptomonnaieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cryptomonnaie")
 */
class CryptomonnaieController extends AbstractController
{
    private $security;
    private $authChecker;

    public function __construct(Security $security, AuthorizationCheckerInterface $authChecker)
    {
        $this->security = $security;
        $this->authChecker = $authChecker;
    }
    /**
     * @Route("/", name="cryptomonnaie_index", methods={"GET"})
     */
    public function index(CryptomonnaieRepository $cryptomonnaieRepository): Response
    {
        //On restreint l'acces aux différentes blockchain uniquement aux administrateurs
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('cryptomonnaie/index.html.twig', [
            'cryptomonnaies' => $cryptomonnaieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cryptomonnaie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //On restreint l'acces aux différentes blockchain uniquement aux administrateurs
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $cryptomonnaie = new Cryptomonnaie();
        $form = $this->createForm(CryptomonnaieType::class, $cryptomonnaie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cryptomonnaie);
            $entityManager->flush();

            return $this->redirectToRoute('cryptomonnaie_index');
        }

        return $this->render('cryptomonnaie/new.html.twig', [
            'cryptomonnaie' => $cryptomonnaie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cryptomonnaie_show", methods={"GET"})
     */
    public function show(Cryptomonnaie $cryptomonnaie): Response
    {
        //On restreint l'acces aux différentes blockchain uniquement aux administrateurs
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('cryptomonnaie/show.html.twig', [
            'cryptomonnaie' => $cryptomonnaie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cryptomonnaie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cryptomonnaie $cryptomonnaie): Response
    {
        //On restreint l'acces aux différentes blockchain uniquement aux administrateurs
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CryptomonnaieType::class, $cryptomonnaie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cryptomonnaie_index');
        }

        return $this->render('cryptomonnaie/edit.html.twig', [
            'cryptomonnaie' => $cryptomonnaie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cryptomonnaie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cryptomonnaie $cryptomonnaie): Response
    {
        //On restreint l'acces aux différentes blockchain uniquement aux administrateurs
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$cryptomonnaie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cryptomonnaie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cryptomonnaie_index');
    }
}

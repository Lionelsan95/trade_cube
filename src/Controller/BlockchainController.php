<?php

namespace App\Controller;

use App\Entity\Blockchain;
use App\Form\BlockchainType;
use App\Repository\BlockchainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blockchain")
 */
class BlockchainController extends AbstractController
{
    /**
     * @Route("/", name="blockchain_index", methods={"GET"})
     */
    public function index(BlockchainRepository $blockchainRepository): Response
    {
        return $this->render('blockchain/index.html.twig', [
            'blockchains' => $blockchainRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="blockchain_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $blockchain = new Blockchain();
        $form = $this->createForm(BlockchainType::class, $blockchain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blockchain);
            $entityManager->flush();

            return $this->redirectToRoute('blockchain_index');
        }

        return $this->render('blockchain/new.html.twig', [
            'blockchain' => $blockchain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blockchain_show", methods={"GET"})
     */
    public function show(Blockchain $blockchain): Response
    {
        return $this->render('blockchain/show.html.twig', [
            'blockchain' => $blockchain,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="blockchain_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Blockchain $blockchain): Response
    {
        $form = $this->createForm(BlockchainType::class, $blockchain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blockchain_index');
        }

        return $this->render('blockchain/edit.html.twig', [
            'blockchain' => $blockchain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blockchain_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Blockchain $blockchain): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blockchain->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blockchain);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blockchain_index');
    }
}

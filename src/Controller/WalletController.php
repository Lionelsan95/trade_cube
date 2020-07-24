<?php

namespace App\Controller;

use App\Entity\CronTask;
use App\Entity\Wallet;
use App\Form\WalletType;
use App\Repository\WalletRepository;
use App\Service\Kraken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/wallet")
 */
class WalletController extends AbstractController
{
    private $authChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * @Route("/", name="wallet_index", methods={"GET"})
     */
    public function index(WalletRepository $walletRepository): Response
    {
        if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $this->render('wallet/index.html.twig', [
                'wallets' => $walletRepository->findAll(),
            ]);
        else
            return $this->render('user_login');
    }

    /**
     * @Route("/new", name="wallet_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $wallet = new Wallet();
            $form = $this->createForm(WalletType::class, $wallet);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();

                /**
                 * 1. connexion à la blockchain
                 * 2. Si connexion ok
                 *  - récupération infos (solde, )
                 *  - vérification de la monnaie
                 *  - Création du crontask
                 *  si le trading a été action
                 *      - activation du Crontask à la fréquence
                 * sinon
                 *  retour à la page de créationa avec le message d'erreur correspondant
                 *
                 */

                $bchain = $this->container->get('blockchain')->getChain($wallet);

                //Création du CronTask lié à ce wallet
                $cron = new CronTask();
                $cron->setEtat(0);//Inactif de base
                $cron->setWallet($wallet);

                $entityManager->persist($wallet);
                $entityManager->persist($cron);

                var_dump($form);

                /*$entityManager->flush();

                return $this->redirectToRoute('wallet_index');*/
            }

            return $this->render('wallet/new.html.twig', [
                'wallet' => $wallet,
                'form' => $form->createView(),
            ]);
        }
        else
            return $this->redirectToRoute('user_login');
    }

    /**
     * @Route("/{id}", name="wallet_show", methods={"GET"})
     */
    public function show(Wallet $wallet): Response
    {
        if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            $trade = $this->container->get('blockchain')->getChain($wallet);
            $history = $trade->getHistory('X' . $wallet->getCryptomonnaie()->getSymbol(), 'EUR', new \DateTime('03/09/2015'));

            return $this->render('wallet/show.html.twig', [
                'wallet' => $wallet,
                'history' => $history
            ]);
        }else
            return $this->render('user_login');
    }

    /**
     * @Route("/{id}/edit", name="wallet_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Wallet $wallet): Response
    {
        if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $form = $this->createForm(WalletType::class, $wallet);
            $form->handleRequest($request);
            $cron = null;

            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $cron = $em->getRepository('App:CronTask')->findOneBy([
                    'wallet' => $wallet->getId()
                ]);

                if (!$cron) {
                    //Création du CronTask lié à ce wallet
                    $cron = new CronTask();
                    $cron->setEtat(0);//Inactif de base
                    $cron->setWallet($wallet);
                    $em->persist($cron);
                }

                $em->flush();

                return $this->redirectToRoute('wallet_index');
            }

            return $this->render('wallet/edit.html.twig', [
                'wallet' => $wallet,
                'form' => $form->createView(),
            ]);
        }else
            return $this->render('user_login');
    }

    /**
     * @Route("/{id}", name="wallet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Wallet $wallet): Response
    {
        if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            if ($this->isCsrfTokenValid('delete' . $wallet->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($wallet);
                $entityManager->flush();
            }

            return $this->redirectToRoute('wallet_index');
        }else
            return $this->render('user_login');
    }
}

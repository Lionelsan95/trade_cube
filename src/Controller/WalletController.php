<?php

namespace App\Controller;

use App\Entity\CronTask;
use App\Entity\Wallet;
use App\Form\WalletType;
use App\Repository\WalletRepository;
use App\Service\BChain;
use App\Service\BlockChain;
use App\Service\CronTab;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/wallet")
 */
class WalletController extends AbstractController
{
    private $authChecker;
    private $blockchain;
    private $cronTab;
    private $security;

    public function __construct(CronTab $cronTab, BChain $blockChain, AuthorizationCheckerInterface $authChecker, Security $security)
    {
        $this->authChecker = $authChecker;
        $this->blockchain = $blockChain;
        $this->cronTab = $cronTab;
        $this->security = $security;
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
                $bchain = null;

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

                try
                {
                    $bchain = $this->blockchain->getChain($wallet);
                }catch (\Exception $e)
                {
                    // Manage error
                    return $this->render('wallet/new.html.twig', [
                        'wallet' => $wallet,
                        'form' => $form->createView(),
                        'error' => "Unable to connect to the API -- ".$e->getMessage()
                    ]);
                }

                if($bchain)
                {
                    //Création du CronTask lié à ce wallet
                    $cron = new CronTask();
                    $cron->setWallet($wallet);

                    $entityManager->persist($wallet);
                    $entityManager->persist($cron);

                    //Activate Crontask
                    //Activate trading cron
                    $this->cronTab->create($wallet);
                    
                    $wallet->setUser($this->security->getUser());

                    $entityManager->flush();

                    return $this->redirectToRoute('wallet_show',['id'=>$wallet->getId()]);

                }else
                {
                    // Manage error
                    return $this->render('wallet/new.html.twig', [
                        'wallet' => $wallet,
                        'form' => $form->createView(),
                        'error'=>'blochain not created'
                    ]);
                }
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
     * @Route("/active_trading", name="active_trading", methods={"POST"})
     */
    public function activateAction(Request $request)
    {
        $data = array();

        if($request->isMethod('POST'))
        {
            $done = array();
            $em = $this->getDoctrine()->getManager();

            foreach (explode(",",$request->request->get('on')) as $signature)
            {
                $wallet = $em->getRepository('App:Wallet')->findOneBy(['signature'=>$signature]);
                if($wallet && $wallet->getTrading() != 1)
                {
                    $wallet->setTrading(1);
                    $done[]=$wallet->getName();

                    //Activate trading cron
                    $this->cronTab->create($wallet);
                }
            }

            foreach (explode(",",$request->request->get('off')) as $signature)
            {
                $wallet = $em->getRepository('App:Wallet')->findOneBy(['signature'=>$signature]);
                if($wallet && $wallet->getTrading() != 0)
                {
                    $wallet->setTrading(0);
                    $done[]=$wallet->getName();

                    //Desactivate trading cron
                    $this->cronTab->remove($wallet);
                }
            }

            $em->flush();

            $data = [
                'result'=>1,
                'updated'=>implode(",", $done)
            ];

        }

        return $this->json($data);
    }

    /**
     * @Route("/{id}", name="wallet_show", methods={"GET"})
     */
    public function show(Wallet $wallet): Response
    {
        if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') &&
            $this->security->getUser()->getId() == $wallet->getUser()->getId() ) {
            $trade = $this->blockchain->getChain($wallet);
            if($trade) {

                $history = $trade->getHistory('X' . $wallet->getCurrency()->getSymbol(), 'EUR', new \DateTime('03/09/2015'));

                return $this->render('wallet/show.html.twig', [
                    'wallet' => $wallet,
                    'history' => $history
                ]);
            }else{
                return $this->render('wallet/show.html.twig', [
                    'wallet' => $wallet,
                    'history' => array()
                ]);
            }
        }else
            return $this->redirectToRoute('user_login');
    }

    /**
     * @Route("/{id}/edit", name="wallet_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Wallet $wallet): Response
    {
        if ( $this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') &&
             $this->security->getUser()->getId() == $wallet->getUser()->getId() )
        {
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
                    $cron->setWallet($wallet);
                    $em->persist($cron);
                }

                $em->flush();

                return $this->redirectToRoute('wallet_show',['id'=>$wallet->getId()]);
            }

            return $this->render('wallet/edit.html.twig', [
                'wallet' => $wallet,
                'form' => $form->createView(),
            ]);
        }else
            return $this->redirectToRoute('user_login');
    }

    /**
     * @Route("/{id}", name="wallet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Wallet $wallet): Response
    {
        if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') &&
            $this->security->getUser()->getId() == $wallet->getUser()->getId() ){
            if ($this->isCsrfTokenValid('delete' . $wallet->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($wallet);
                $entityManager->flush();
            }

            return $this->redirectToRoute('wallet_index');
        }else
            return $this->redirectToRoute('user_login');
    }
}

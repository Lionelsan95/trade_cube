<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    private $security;
    private $authChecker;

    public function __construct(Security $security, AuthorizationCheckerInterface $authChecker)
    {
        $this->security = $security;
        $this->authChecker = $authChecker;
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function indexAction(){
        return $this->redirectToRoute('user_login');
    }
}
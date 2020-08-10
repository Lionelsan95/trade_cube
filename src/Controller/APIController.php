<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
/**
 * Class APIController
 * @package App\Controller
 * @Route("/api", name="api_")
 */
class APIController extends AbstractController
{

    private $passwordEncoder;
    private $JWTEncoder;

    public function __construct(UserPasswordEncoder $passwordEncoder, LcobucciJWTEncoder $JWTEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->JWTEncoder = $JWTEncoder;
    }


    /**
     * @Route("/tokens", name="tokens", methods={"POST"})
     */
    public function token(Request $request)
    {
        $username = $request->request->get('user') ?? '';
        $password = $request->request->get('password') ?? '';

        $user = $this->getDoctrine()->getManager()->getRepository('App:User')->findOneBy(
            [
                'username'=>$username
            ]
        );

        if(!$user)
            throw $this->createNotFoundException();

        $isValid = $this->passwordEncoder->isPasswordValid($user, $this->passwordEncoder->encodePassword($user,$password));

        if($isValid)
            throw new BadCredentialsException();

        $token = $this->JWTEncoder
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);

        return new JsonResponse([
            'token'=>$token,
            'expired'=> '1 hour'
        ]);
    }
}

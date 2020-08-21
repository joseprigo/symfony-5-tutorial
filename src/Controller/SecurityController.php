<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Description of SecurityController
 *
 * @author josep
 */
class SecurityController extends AbstractController {

    /**
     * @var \Twig_Environment
     */
    
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils){
        return $this->render(
                'security/login.html.twig',
                [
                    'last_username' => $authenticationUtils->getLastUsername(),
                    'error' => $authenticationUtils->getLastAuthenticationError()
                ]);
    }
    
    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){
        
    }
    
    /**
     * @Route("/confirm/{token}", name="security_confirm")
     */
    public function confirm(string $token, UserRepository $userRepository,
            EntityManagerInterface $entityManager){
        $user = $userRepository->findOneBy([
            'confirmationToken' => $token
        ]);
        
        if (null !== $user ){
            $user->setEnabled(true);
            $user->setConfirmationToken('');
            $entityManager->flush();
        }
        
        return new Response(
                $this->render('security/confirmation.html.twig',
                [
                    'user' => $user
                ]
                        )
                );
    }
}

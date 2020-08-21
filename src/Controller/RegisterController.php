<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Event\UserRegisterEvent;
use App\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Description of SecurityController
 *
 * @author josep
 */
class RegisterController extends AbstractController {

    /**
     * @var \Twig_Environment
     */
    
    /**
     * @Route("/register", name="user_register")
     */
    public function register(
            UserPasswordEncoderInterface $passwordEncoder,
            Request $request,
            EventDispatcherInterface $eventDispatcher,
            TokenGenerator $tokenGenerator){
        $user = new User();
        $form = $this->createForm(
                UserType::class, $user
                );
        $form->handlerequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword(
                    $user,
                    $user->getPlainPassword()
                    );
            $user->setPassword($password);
            $user->setConfirmationToken($tokenGenerator->getRandomSecureToken(30));
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            $userEvent = new UserRegisterEvent($user);
            $eventDispatcher->dispatch(
                    $userEvent,
                    UserRegisterEvent::NAME
                    );

            return $this->redirectToRoute('micro_post_index');
        }
        
        
        return $this->render('register/register.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    
}

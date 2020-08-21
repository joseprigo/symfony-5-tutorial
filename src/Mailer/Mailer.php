<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mailer;

use App\Entity\User;

/**
 * Description of Mailer
 *
 * @author josep
 */
class Mailer {

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig\Environment
     */
    private $twig;
    
    /**
     * @var string 
     */
    private $mailFrom;

    public function __construct(
            \Swift_Mailer $mailer,
            \Twig\Environment $twig,
            string $mailFrom
            ) {
        
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }
    
    public function sendConfirmationEmail(User $user){
        $body = $this->twig->render(
                'email/registration.html.twig'
                , [
                    'user' => $user
                ]);
        
        $message = (new \Swift_Message())
                ->setSubject('Welcome to the micro-post app')
                ->setFrom($this->mailFrom)
                ->setTo($user->getEmail())
                ->setBody($body,'text/html');
        $this->mailer->send($message);
    }
}

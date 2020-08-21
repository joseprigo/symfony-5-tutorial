<?php

namespace App\Tests\Mailer;

use App\Entity\User;
use App\Mailer\Mailer;
use PHPUnit\Framework\TestCase;

class MailerTest extends TestCase{
    public function testConfirmationEmail(){
        $user = new User();
        $user->setEmail('josjo@cuestos.es');
        
        //simulate a dependency
        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)->
                disableOriginalConstructor()
                ->getMock();
        
        //simulate a dependency method
        $swiftMailer->expects($this->once())->method('send')->
                with(
                        $this->callback(function($subject){
                            $messageStr = (string) $subject;
                            return strpos($messageStr, "From: me@jomateix.cat") !== false
                                    && strpos($messageStr, "Content-Type: text/html") !== false
                                    && strpos($messageStr, "This is a message body") !== false;
                        })
                );
        
        
        $twigMock = $this->getMockBuilder(\Twig\Environment::class)->
                disableOriginalConstructor()
                ->getMock();
        $twigMock->expects($this->once())->method('render')
                ->with(
                        'email/registration.html.twig',
                        [ 'user' => $user ]
                        )->willReturn('This is a message body');
        
        $mailer = new Mailer($swiftMailer, $twigMock, 'me@jomateix.cat');
        $mailer->sendConfirmationEmail($user);
    }
}

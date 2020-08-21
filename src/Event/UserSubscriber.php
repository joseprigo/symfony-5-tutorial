<?php

namespace App\Event;

use App\Mailer\Mailer;
use App\Entity\UserPreferences;
use App\Event\UserRegisterEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class UserSubscriber implements EventSubscriberInterface {

    /**
     * @var string
     */
    private $default_locale;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer, EntityManagerInterface $entityManager, string $defaultLocale) {
        
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->default_locale = $defaultLocale;
    }

    
    public static function getSubscribedEvents(){
        return  [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }
    
    public function onUserRegister(UserRegisterEvent $event){
        
        $preferences = new UserPreferences();
        $preferences->setLocale($this->default_locale);
        $event->getRegisteredUser()->setPreferences($preferences);
        
        $this->entityManager->flush();
        
        $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
        
    }

}

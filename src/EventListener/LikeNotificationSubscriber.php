<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\EventListener;

use App\Entity\MicroPost;
use App\Entity\LikeNotification;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollecion;

/**
 * Description of newPHPClass
 *
 * @author josep
 */
class LikeNotificationSubscriber implements EventSubscriber {
    public function getSubscribedEvents(){
        return [
            Events::onFlush
        ];
    }
    
    public function onFlush(OnFlushEventArgs $args){
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        
        /** @var PersistentCollection $collectionUpdate */
        foreach($uow->getScheduledCollectionUpdates() as $collectionUpdate){
            if (!$collectionUpdate->getOwner() instanceof MicroPost){
                continue;
            }
           if( 'likedBy' !== $collectionUpdate->getMapping()['fieldName']){
               continue;
           }
           
           $insertDiff = $collectionUpdate->getInsertDiff();
           
           if(!count($insertDiff)){
               return;
           }
           
           /** @var MicroPost $microPost */
           $microPost = $collectionUpdate->getOwner();
           
           $notification = new LikeNotification();
           $notification->setUser($microPost->getUser());
           $notification->setMicroPost($microPost);
           $notification->setLikedby(reset($insertDiff));
           
           $em->persist($notification);
           
           $uow->computeChangeSet(
                   $em->getClassMetadata(LikeNotification::class),
                   $notification
                   );
        }
    }
}

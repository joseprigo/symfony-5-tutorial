<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Security;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Description of MicroPostVoter
 *
 * @author josep
 */
class MicroPostVoter extends Voter{

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    const EDIT = 'edit';
    const DELETE = 'delete';
    
    public function __construct(AccessDecisionManagerInterface $decisionManager) {
        $this->decisionManager = $decisionManager;
    }
    /**
     * 
     * @param type $attribute action to check
     * @param type $subject entity
     * @return boolean
     */
    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::EDIT, self::DELETE])){
            return false;
        }
        if(!$subject instanceof MicroPost ){
            return false;
        }
        return true;
    }
    
    /**
     * 
     * @param type $attribute
     * @param type $subject
     * @param TokenInterface $token currently authenticated user
     * @return boolean
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if($this->decisionManager->decide($token, [User::ROLE_ADMIN])){
            return true;
        }
        $authenticatedUser = $token->getUser();
        
        if(!$authenticatedUser instanceof User){
            return false;
        }
        
        /** the subject has already been checked in supports() method*/
        $microPost = $subject;
        
        return $microPost->getUser()->getId() === $authenticatedUser->getId();
    }
}

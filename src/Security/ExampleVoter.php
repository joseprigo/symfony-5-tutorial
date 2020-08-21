<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Description of ExampleVoter
 *
 * @author josep
 */
class ExampleVoter implements VoterInterface {
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        return false;
    }
}

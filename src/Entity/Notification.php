<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"like" = "LikeNotification"})
 */
abstract class Notification {
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue()
    * @ORM\Column(type="integer")
    */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $seen;
    
    public function __construct() {
        $this->seen = false;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getUser() {
        return $this->user;
    }

    public function getSeen() {
        return $this->seen;
    }

    public function setUser($user): void {
        $this->user = $user;
    }

    public function setSeen($seen): void {
        $this->seen = $seen;
    }





}

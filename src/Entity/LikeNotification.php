<?php

namespace App\Entity;

use App\Repository\LikeNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikeNotificationRepository::class)
 */
class LikeNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost")
     */
    private $microPost;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $likedBy;
    
    public function getMicroPost() {
        return $this->microPost;
    }

    public function getLikedBy() {
        return $this->likedBy;
    }

    public function setMicroPost($microPost): void {
        $this->microPost = $microPost;
    }

    public function setLikedBy($likedBy): void {
        $this->likedBy = $likedBy;
    } 


}

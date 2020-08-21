<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=280)
     * @Assert\NotBlank()
     * @Assert\Length(min=10, minMessage="This is not enough")
     */
    private $text;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $time;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false) 
     */
    private $user;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="postsLiked")
     * @ORM\JoinTable(name="post_likes",
     *  joinColumns={
     *      @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *  }
     * )
     */
    private $likedBy;
    
    public function __construct() {
        $likedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getText() {
        return $this->text;
    }

    public function getTime() {
        return $this->time;
    }

    public function setText($text): void {
        $this->text = $text;
    }

    public function setTime($time): void {
        $this->time = $time;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist(): void {
        $this->time = new \DateTime();
    }
    
    public function getUser() {
        return $this->user;
    }

    public function setUser($user): void {
        $this->user = $user;
    }
    
    /**
     * @return Collection
     */
    public function getLikedBy() {
        return $this->likedBy;
    }
    
    public function like(User $user){
        if($this->likedBy->contains($user)){
            return;
        }
        $this->likedBy->add($user);
    }








}

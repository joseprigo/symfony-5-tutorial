<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email",  message="This e-mail is already used") * @UniqueEntity(fields="email",  message="This e-mail is already used")
 * @UniqueEntity(fields="username",  message="This username is already used")

 */
class User implements UserInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /** 
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=50)
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $password;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=4096)
     */
    private $fullName;
    /**
     * @var array
     * @ORM\Column(type="simple_array")
     */
    private $roles;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user") 
     */
    private $posts;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="following")
     */
    private $followers;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="followers")
     * @ORM\JoinTable(name="following",
     *  joinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="following_user_id", referencedColumnName="id")
     *  }
     * )
     */
    private $following;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MicroPost", mappedBy="likedBy")
     */
    private $postsLiked;
    
    /**
     * @ORM\Column(type="string", nullable=true, length=30)
     */
    private $confirmationToken;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;
    
    /**
     *  @ORM\OneToOne(targetEntity="App\Entity\UserPreferences", cascade={"persist"})
     */
    private $preferences;
    
    public function __construct() {
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->postsLiked = new ArrayCollection();
        $this->roles = [self::ROLE_USER];
        $this->enabled = false;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void {
        $this->roles = $roles;
    }
    
    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {

    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->id,
            $this->username,
            $this->password) = unserialize($serialized);
    }
    
    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFullName() {
        return $this->fullName;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function setFullName($fullName): void {
        $this->fullName = $fullName;
    }
    
    public function setUsername($username): void {
        $this->username = $username;
    }

    public function setPassword($password): void {
        $this->password = $password;
    }
    
    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): void {
        $this->plainPassword = $plainPassword;
    }
    
    public function getPosts() {
        return $this->posts;
    }
    
    /**
     * @return Collection
     */
    public function getFollowers() {
        return $this->followers;
    }

    /**
     * @return Collection
     */
    public function getFollowing() {
        return $this->following;
    }
    
    public function follow(User $userToFollow){
        if($this->getFollowing()->contains($userToFollow)){
            return;
        }
        else{
            $this->getFollowing()->add($userToFollow);
        }
    }
    
    /**
     * 
     * @return Collection
     */
    public function getPostsLiked() {
        return $this->postsLiked;
    }
    
    public function getConfirmationToken() {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($confrimationToken): void {
        $this->confirmationToken = $confrimationToken;
    }
    
    public function getEnabled() {
        return $this->enabled;
    }

    public function setEnabled($enabled): void {
        $this->enabled = $enabled;
    }
    
    /**
     * @return UserPreferences|null
     */
    public function getPreferences() {
        return $this->preferences;
    }

    public function setPreferences($preferences): void {
        $this->preferences = $preferences;
    }


}
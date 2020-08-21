<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController{

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    private $twig;

    function __construct( MicroPostRepository $microPostRepository,
            FormFactoryInterface $formFactory,
            EntityManagerInterface $entityManager,
            RouterInterface $router,
            FlashBagInterface  $flashBag
            ) {
        
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    
    /**
     * @Route("/", name="micro_post_index")
     */
    public function index(\App\Repository\UserRepository $userRepo)
    {
        $usersToFollow = [];
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        
        /**
         * if the user is authenticated get only the posts of the users it is following
         */
        if($currentUser instanceof User){
            
            $posts = $this->microPostRepository
                    ->findAllByUsers(
                            $currentUser->getFollowing()
                             );
            $usersToFollow = count($posts) === 0 ?
                    $userRepo->findAllWithMoreThan5PostsExceptUser(
                            $currentUser
                            ) : [];
        }else{
            $posts = $this->microPostRepository
                        ->findBy(
                                [],
                                ['time' => 'DESC']
                        );
        }
        
        return $this->render('micro-post/index.html.twig',
                [
                    'posts' => $posts,
                    'usersToFollow' => $usersToFollow
                ]);
    }
    
    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     */
    public function edit(MicroPost $microPost, Request $request) {
        
        $this->denyAccessUnlessGranted('edit', $microPost);
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // NO NEED FOR PERSIST WHEN EDITING $this->entityManager->persist($microPost);
            $this->entityManager->flush();
            
            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
        
        return $this->render('micro-post/add.html.twig',
                ['form' => $form->createView()]);
    }


    /**
     * @Route("/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     */
    public function add(Request $request)
    {
        $user = $this->getUser();
        
        $microPost = new MicroPost();
        $microPost->setUser($user);
        
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();
            
            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
        
        return $this->render('micro-post/add.html.twig',
                ['form' => $form->createView()]);
    }
    
    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     */
    public function remove(MicroPost $microPost) {
        
        $this->denyAccessUnlessGranted('delete', $microPost);
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();
        $this->flashBag->add('notice', 'Micro post was deleted');
        
        return new RedirectResponse($this->router->generate('micro_post_index'));

    }
    
    /**
     * @Route("/user/{username}", name="micro_post_user")
     */
    public function userPosts(User $userWithPosts) {
        return $this->render('micro-post/user-post.html.twig',
                [
                    /*'posts' => $this->microPostRepository->findBy(
                            ['user' => $userWithPosts],
                            ['time' => 'DESC'])*/
                    'user' => $userWithPosts,
                    'posts' => $userWithPosts->getPosts()
                ]);
    }


    /**
     * @Route("/{id}", name="micro_post_post")
     */
    public function post(MicroPost $post){
        return $this->render('micro-post/post.html.twig',
                [
                    'post' => $post
                ]);
    }
}

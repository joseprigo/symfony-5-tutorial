<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form;

use App\Entity\MicroPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



/**
 * Description of MicroPostType
 *
 * @author josep
 */
class MicroPostType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('text', TextareaType::class, ['label' => false])
                ->add('save', SubmitType::class);
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class' => MicroPost::class
        ]);
    }
}

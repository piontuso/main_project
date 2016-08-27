<?php

namespace Mp\GithubBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PullRequestType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder->add('title', TextType::class, array(
        'label' => 'Title',
        'attr' => array('class' => 'form-control'),
      ))->add('body', TextareaType::class, array(
      'label' => 'Body',
        'attr' => array(
          'class' => 'form-control',
          'rows' => '2'
        ),
      ));

      $selectOptions = $options['data']->getBranches();

      $builder->add('base', ChoiceType::class, array(
        'label' => 'Base',
        'choices' => $selectOptions,
        'attr' => array('class' => 'form-control'),
      ));
      $builder->add('head', ChoiceType::class, array(
        'label' => 'Compare',
        'choices' => $selectOptions,
        'attr' => array('class' => 'form-control'),
      ));

      $builder->add('merge', SubmitType::class, array(
        'label' => 'Create Pull Request',
        'attr' => array('class' => 'btn btn-default'),
      ));
  }

  public function getName()
  {
    return 'pull_request';
  }
}
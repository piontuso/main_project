<?php

namespace Mp\GithubBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GithubFileType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $selectOptions = $options['data']->getBranchOptions();
    if (!empty($selectOptions)) {
      $builder->add('branchName', ChoiceType::class, array(
        'choices' => $selectOptions,
        'attr' => array('class' => 'form-control'),
      ));
    }

    $builder->add('fileName', TextType::class, array(
      'attr' => array(
        'class' => 'form-control',
        'disabled' => $options['name_disabled'],
      ),
    ))
      ->add('fileContent', TextareaType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'rows' => '5'
        ),
      ))
      ->add('create', SubmitType::class, array(
        'label' => $options['button_label'],
        'attr' => array('class' => 'btn btn-primary'),
      ));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'button_label' => 'Create',
      'name_disabled' => FALSE,
    ));
  }

  public function getName()
  {
    return 'file';
  }
}
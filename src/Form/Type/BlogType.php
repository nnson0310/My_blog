<?php

namespace App\Form\Type;

use App\Entity\Blog;
use App\Entity\Tags;
use App\Entity\Topic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{

  public function buildForm(FormBuilderInterface $formBuilder, array $options)
  {
    $formBuilder
      ->add('title', TextType::class)
      ->add('thumbnail', FileType::class, array(
        'attr' => [
          'accept' => 'image/*'
        ],
        'required' => false,
        'mapped' => false
      ))
      ->add('tags', EntityType::class, array(
        'class' => Tags::class,
        'required' => true,
        'label_attr' => array(
          'class' => 'checkbox-inline',
        ),
        'expanded' => true,
        'multiple' => true
      ))
      ->add('topics', EntityType::class, array(
        'class'  => Topic::class,
        'choice_label' => function (Topic $topic) {
          return $topic->getName();
        },
        'placeholder' => 'Choose a topic'
      ))
      ->add('description', TextType::class)
      ->add('content', CKEditorType::class, array(
        'config' => array(
          'allowedContent' => true
        )
      ))
      ->add('min_read', NumberType::class, [
        'html5' => true,
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Blog::class
    ]);
  }
}

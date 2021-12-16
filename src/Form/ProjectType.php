<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Project;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProjectType extends AbstractType
{
   

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      
    
        $builder
           
            ->add('title')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true
            ])
            ->add('description', CKEditorType::class)
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('github')
            ->add('weblink');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

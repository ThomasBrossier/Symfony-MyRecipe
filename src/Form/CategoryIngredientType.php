<?php

namespace App\Form;

use App\Entity\CategoryIngredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CategoryIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom'
            ])
            ->add('imageFile',VichImageType::class,[
                'label'=> "Photo de la catégorie",
                'required'=>false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'maxSizeMessage'=> "La taille de l\'image doit être inférieure à {{ limit }}",
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/webp'

                        ],
                        'mimeTypesMessage' => 'Veuillez fournir une image valide',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryIngredient::class,
        ]);
    }
}

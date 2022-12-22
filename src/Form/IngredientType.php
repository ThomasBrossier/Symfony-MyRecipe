<?php

namespace App\Form;

use App\Entity\CategoryIngredient;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=> "Nom"
            ])
            ->add('imageFile', VichImageType::class,[
                'label'=> "Photo de l'ingrédient",
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
            ->add('type', ChoiceType::class,[
                'autocomplete'=>true,
                'placeholder'=>"Selectionner un type" ,
                'choices'=> [
                    "Solide" => "Solide",
                    "Liquide" => "Liquide"
                ],
            ])
            ->add('category', EntityType::class,[
                'label' => 'Catégorie de l\'ingrédient',
                'class'=> CategoryIngredient::class,
                'autocomplete' => true,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}

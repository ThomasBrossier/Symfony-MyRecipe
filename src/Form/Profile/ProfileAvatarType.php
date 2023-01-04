<?php

namespace App\Form\Profile;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfileAvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class,[
                'label'=> "Photo de profil",
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
            'data_class' => Profile::class,
        ]);
    }
}

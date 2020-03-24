<?php

namespace App\Form;

use App\Entity\Informer;
use App\Validator\constraints\Base64StringConstraint;
use App\Validator\constraints\FileMimeTypeConstraint;
use App\Validator\constraints\FileSizeConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class InformerType
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class InformerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('phoneNumber')
            ->add('zipCode')
            ->add('culpableFirstName')
            ->add('culpableLastName')
            ->add('culpableAddress')
            ->add('comment')
            ->add('file', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new Base64StringConstraint(),
                    new FileSizeConstraint([
                        'size' => 2048
                    ]),
                    new FileMimeTypeConstraint([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ]
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Informer::class,
        ]);
    }
}

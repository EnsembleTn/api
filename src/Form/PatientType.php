<?php

namespace App\Form;

use App\Entity\Patient;
use App\Validator\constraints\Base64StringConstraint;
use App\Validator\constraints\FileMimeTypeConstraint;
use App\Validator\constraints\FileSizeConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PatientType
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class PatientType extends AbstractType
{
    CONST SIZE = 4000000 ; // 4Mo

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('zipCode')
            ->add('phoneNumber')
            ->add('gender')
            ->add('audio', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    //new NotBlank(),
                    new Base64StringConstraint(),
                    new FileSizeConstraint([
                        'size' => self::SIZE
                    ]),
                    new FileMimeTypeConstraint([
                        'mimeTypes' => [
                            'audio/mpeg', //mp3
                            'audio/mp4',
                            'audio/ogg',
                            'audio/webm'
                        ]
                    ])
                ],
            ])
            ->add('responses', CollectionType::class, [
                'entry_type' => ResponseType::class,
                'allow_add' => false,
                'allow_delete' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}

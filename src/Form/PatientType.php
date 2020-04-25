<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\SMSVerification;
use App\Util\Tools;
use App\Validator\constraints\Base64StringConstraint;
use App\Validator\constraints\FileMimeTypeConstraint;
use App\Validator\constraints\FileSizeConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PatientType
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class PatientType extends AbstractType
{
    const MAX_AUDIO_SIZE = 4000000; // 4Mo

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('city', ChoiceType::class, [
                'choices' => Tools::tunisiaCitiesList()
            ])
            ->add('zipCode')
            ->add('phoneNumber')
            ->add('gender', ChoiceType::class, [
                'choices' => Patient::getGendersList()
            ])
            ->add('audio', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new Base64StringConstraint(),
                    new FileSizeConstraint([
                        'size' => self::MAX_AUDIO_SIZE
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
            ->add('comment')
            ->add('responses', CollectionType::class, [
                'entry_type' => ResponseType::class,
                'allow_add' => $options['allow_extra_fields'],
                'by_reference' => !$options['allow_extra_fields'],
                'allow_delete' => false,
            ])
            ->add('pinCode', IntegerType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => SMSVerification::PIN_CODE_MAX_LENGTH,
                        'max' => SMSVerification::PIN_CODE_MAX_LENGTH,
                        'exactMessage' => "The pin code should have exactly {{ limit }} characters"
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
            'allow_extra_fields' => false,
        ]);
    }
}

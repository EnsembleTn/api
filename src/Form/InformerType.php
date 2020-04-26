<?php

namespace App\Form;

use App\Entity\Informer;
use App\Entity\SMSVerification;
use App\Validator\constraints\Base64StringConstraint;
use App\Validator\constraints\FileMimeTypeConstraint;
use App\Validator\constraints\FileSizeConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class InformerType
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class InformerType extends AbstractType
{
    CONST SIZE = 2000000 ; // 2Mo

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
            ->add('image', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Base64StringConstraint(),
                    new FileSizeConstraint([
                        'size' => self::SIZE
                    ]),
                    new FileMimeTypeConstraint([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ]
                    ])
                ],
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
            'data_class' => Informer::class,
        ]);
    }
}

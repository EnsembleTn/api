<?php

namespace App\Form;

use App\Entity\Doctor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationType
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class RegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('plainPassword')
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('phoneNumber')
            ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'ROLE_DOCTOR' => 'ROLE_DOCTOR',
                        'ROLE_EMERGENCY_DOCTOR' => 'ROLE_EMERGENCY_DOCTOR',
                    ],
                    'multiple' => true,
                    'required' => true,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
            'validation_groups' => ['Doctor', 'registration']
        ]);
    }
}

<?php

namespace App\Form;

use App\Dto\Phone;
use App\Entity\SMSVerification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PhoneType
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class PhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', IntegerType::class)
            ->add('type', ChoiceType::class, [
                'choices' => SMSVerification::getTypes()
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Phone::class,
        ]);
    }
}

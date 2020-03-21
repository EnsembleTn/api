<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/

namespace App\Form;


use App\Entity\Doctor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['required' => false])
            ->add('lastName', TextType::class, ['required' => false])
            ->add('address', TextType::class, ['required' => false])
            ->add('phoneNumber', TextType::class, ['required' => false])
            ->add('category', TextType::class, ['required' => false])
            ->add('region', TextType::class, ['required' => false])
            ->add('phoneNumber', IntegerType::class, ['required' => false])

        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
        ]);
    }
}
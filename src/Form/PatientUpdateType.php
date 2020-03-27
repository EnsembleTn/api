<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PatientUpdateType
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class PatientUpdateType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emergencyStatus', ChoiceType::class, [
                'choices' => Patient::getStatusesList(true)
            ])
            ->add('flag', ChoiceType::class, [
                'choices' => Patient::getFlagsList()
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {

            $form = $event->getForm();

            /** @var Doctor $doctor */
            $doctor = $options['doctor'];

            // Emergency doctor can not set the flag field && Doctor can not set the emergencyStatus field
            $doctor->isEmergencyDoctor() ? $form->remove('flag') : $form->remove('emergencyStatus');

        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
            'doctor' => null
        ]);
    }
}

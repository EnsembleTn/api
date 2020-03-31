<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class PatientAdmin
 *
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @author : Ghaith Daly <daly.ghaith@gmail.com>
 **/
final class PatientAdmin extends AbstractAdmin
{
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('firstName', TextType::class);
        $showMapper->add('lastName', TextType::class);
        $showMapper->add('gender', TextType::class);
        $showMapper->add('address', TextType::class);
        $showMapper->add('zipCode');
        $showMapper->add('phoneNumber');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $showMapper->add('status');
            $showMapper->add('emergencyStatus');
            $showMapper->add('flag');
            $showMapper->add('medicalStatus');
            $showMapper->add('isTestPositiveAsString', null, ['label' => 'Test Positive']);
            $showMapper->add('isDenouncedAsString', null, ['label' => 'Denounced']);
            $showMapper->add('doctor');
            $showMapper->add('doctorSms');
            $showMapper->add('emergencyDoctor');
            $showMapper->add('emergencyDoctorSms');
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('firstName');
        $datagridMapper->add('lastName');
        $datagridMapper->add('address');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $datagridMapper->add('status');
            $datagridMapper->add('emergencyStatus');
            $datagridMapper->add('flag');
        } else {
            $datagridMapper->add('phoneNumber');
            $datagridMapper->add('zipCode');
        }
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('firstName');
        $listMapper->addIdentifier('lastName');
        $listMapper->addIdentifier('address');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $listMapper->addIdentifier('status');
            $listMapper->addIdentifier('emergencyStatus');
            $listMapper->addIdentifier('flag');
            $listMapper->addIdentifier('isDenouncedAsString', null, ['label' => 'Denounced']);
        } else {
            $listMapper->addIdentifier('phoneNumber');
            $listMapper->addIdentifier('zipCode');
        }

        $listMapper->add('_action', 'actions', ['actions' => ['delete' => ['template' => ':CRUD:list__action_delete.html.twig'], 'show' => ['template' => ':CRUD:list__action_show.html.twig']]]);

    }

    public function createQuery($context = 'list')
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return parent::createQuery($context);
        } else {
            $query = parent::createQuery($context);
            $query->andWhere(
                $query->expr()->in($query->getRootAlias() . '.denounced', 1));
            return $query;
        }
    }
}
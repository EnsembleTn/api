<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 22‏/3‏/2020, Sun
 **/

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class PatientAdmin extends AbstractAdmin
{
    public function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('edit')
            ->remove('create');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('firstName', TextType::class);
        $formMapper->add('lastName', TextType::class);
        $formMapper->add('address', TextType::class);
        $formMapper->add('zipCode', TextType::class);
        $formMapper->add('phoneNumber', TextType::class);

    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('firstName', TextType::class);
        $showMapper->add('lastName', TextType::class);
        $showMapper->add('address', TextType::class);
        $showMapper->add('zipCode');
        $showMapper->add('phoneNumber');
        $showMapper->add('status');
        $showMapper->add('emergencyStatus');
        $showMapper->add('flag');
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {

            $showMapper->add('isDecouncedAsString', null, ['label' => 'Denounced']);
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('firstName');
        $datagridMapper->add('lastName');
        $datagridMapper->add('address');
        $datagridMapper->add('zipCode');
        $datagridMapper->add('phoneNumber');
        $datagridMapper->add('status');
        $datagridMapper->add('emergencyStatus');
        $datagridMapper->add('flag');
        $datagridMapper->add('denounced');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('firstName');
        $listMapper->addIdentifier('lastName');
        $listMapper->addIdentifier('address');
        $listMapper->addIdentifier('zipCode');
        $listMapper->addIdentifier('phoneNumber');
        $listMapper->addIdentifier('status');
        $listMapper->addIdentifier('emergencyStatus');
        $listMapper->addIdentifier('flag');
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $listMapper->addIdentifier('isDecouncedAsString', null, ['label' => 'Denounced']);
        }
        $listMapper->add('_action', 'actions', ['actions' => ['edit' => ['template' => ':CRUD:list__action_edit.html.twig'], 'delete' => ['template' => ':CRUD:list__action_delete.html.twig'], 'show' => ['template' => ':CRUD:list__action_show.html.twig']]]);

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
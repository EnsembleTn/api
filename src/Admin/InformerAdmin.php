<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 27‏/3‏/2020, Fri
 **/

namespace App\Admin;


use App\Util\Tools;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class InformerAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('guid')
            ->add('firstName')
            ->add('lastName');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('firstName')
            ->add('lastName')
            ->add('comment')
            ->add('address')
            ->add('zipCode')
            ->add('phoneNumber')
            ->add('culpableFirstName')
            ->add('culpableLastName')
            ->add('culpableAddress')
            ->add('_action', 'actions', ['actions' => ['edit' => ['template' => ':CRUD:list__action_edit.html.twig'], 'delete' => ['template' => ':CRUD:list__action_delete.html.twig'], 'show' => ['template' => ':CRUD:list__action_show.html.twig']]]);

    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper->add('firstName');
        $formMapper->add('lastName');
        $formMapper->add('address');
        $formMapper->add('phoneNumber');
        $formMapper->add('zipCode');
        $formMapper->add('culpableFirstName');
        $formMapper->add('culpableLastName');
        $formMapper->add('culpableAddress');
        $formMapper->add('comment');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('guid')
            ->add('firstName')
            ->add('lastName');
    }

    public function prePersist($informer)
    {
        $informer->setGuid(Tools::generateGUID('INF', 8));
    }
}
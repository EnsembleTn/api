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

final class ResponseAdmin extends AbstractAdmin
{
    public function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('edit')
            ->remove('create');
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('value');
        $showMapper->add('patient');
        $showMapper->add('question');

    }
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('value');
        $datagridMapper->add('patient');
        $datagridMapper->add('question');

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('value');
        $listMapper->add('patient');
        $listMapper->add('question');
        $listMapper->add('_action','actions', ['actions'=>['edit' =>['template' => ':CRUD:list__action_edit.html.twig'], 'delete' => ['template' => ':CRUD:list__action_delete.html.twig'], 'show' => ['template' => ':CRUD:list__action_show.html.twig']]]);

    }
}
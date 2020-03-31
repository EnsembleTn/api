<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class ResponseAdmin
 *
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @author : Ghaith Daly <daly.ghaith@gmail.com>
 **/

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
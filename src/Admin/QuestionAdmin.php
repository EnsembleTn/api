<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class QuestionAdmin
 *
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @author : Ghaith Daly <daly.ghaith@gmail.com>
 **/
final class QuestionAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('frValue', TextType::class);
        $formMapper->add('arValue', TextType::class);


    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('frValue', TextType::class);
        $showMapper->add('arValue', TextType::class);
        $showMapper->add('type', TextType::class);
        $showMapper->add('category', TextType::class);

    }
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('frValue');
        $datagridMapper->add('arValue');
        $datagridMapper->add('type');
        $datagridMapper->add('category');

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('frValue');
        $listMapper->add('arValue');
        $listMapper->add('type');
        $listMapper->add('category');
        $listMapper->add('_action','actions', ['actions'=>['edit' =>['template' => ':CRUD:list__action_edit.html.twig'], 'delete' => ['template' => ':CRUD:list__action_delete.html.twig'], 'show' => ['template' => ':CRUD:list__action_show.html.twig']]]);

    }
}
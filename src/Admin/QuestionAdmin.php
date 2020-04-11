<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        $formMapper->add('frValue', TextType::class, ['label' => 'French value']);
        $formMapper->add('arValue', TextType::class, ['label' => 'French value']);
        $formMapper->add('type', ChoiceType::class, [
                'choices' => [
                    'TYPE_YES_OR_NO' => 1,
                    'TYPE_NORMAL' => 2,
                    'TYPE_NUMBER' => 3,
                ],
                'multiple' => false,
                'required' => true,
            ]
        );
        $formMapper->add('category', ChoiceType::class, [
                'choices' => [
                    'CATEGORY_GENERAL' => 1,
                    'CATEGORY_ANTECEDENT' => 2,
                    'CATEGORY_SYMPTOMS' => 3,
                ],
                'multiple' => false,
                'required' => true,
            ]
        );

    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('frValue', TextType::class, ['label' => 'French value']);
        $showMapper->add('arValue', TextType::class, ['label' => 'French value']);
        $showMapper->add('getTypeAsString', TextType::class, ['label' => 'Type']);
        $showMapper->add('getCategoryAsString', TextType::class, ['label' => 'Category']);

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('frValue', null, ['label' => 'French value']);
        $datagridMapper->add('arValue', null, ['label' => 'Arabic value']);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('frValue', null, ['label' => 'French value']);
        $listMapper->add('arValue', null, ['label' => 'Arabic value']);
        $listMapper->add('getTypeAsString', null, ['label' => 'Type']);
        $listMapper->add('getCategoryAsString', null, ['label' => 'Category']);
        $listMapper->add('_action', 'actions', ['actions' => ['edit' => ['template' => ':CRUD:list__action_edit.html.twig'], 'delete' => ['template' => ':CRUD:list__action_delete.html.twig'], 'show' => ['template' => ':CRUD:list__action_show.html.twig']]]);

    }
}
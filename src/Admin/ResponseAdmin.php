<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

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
        $collection->remove('create');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('patient');
        $listMapper->add('question.frValue', null, ['label' => 'Question']);
        $listMapper->add('value', null, ['label' => 'Response']);
    }

    public function getExportFields()
    {
        return ['patient', 'question.frValue', 'value'];
    }
}
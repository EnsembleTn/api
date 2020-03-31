<?php

namespace App\Admin;

use App\Manager\FileManager;
use App\Util\Tools;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class InformerAdmin
 *
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @author : Ghaith Daly <daly.ghaith@gmail.com>
 **/
final class InformerAdmin extends AbstractAdmin
{
    /**
     * @var FileManager
     */
    private $fm;

    /**
     * @var string
     */
    public $image = '';

    /**
     * InformerAdmin constructor.
     * @param $code
     * @param $class
     * @param $baseControllerName
     * @param FileManager $fm
     */
    public function __construct($code, $class, $baseControllerName, FileManager $fm)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->fm = $fm;
    }

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
        $this->image = $this->fm->getFile($this->getSubject());
        $showMapper
            ->add('id')
            ->add('guid')
            ->add('firstName')
            ->add('phoneNumber')
            ->add('zipCode')
            ->add('culpableFirstName')
            ->add('culpableLastName')
            ->add('culpableAddress')
            ->add('comment')
            ->add('image', null, ['template' => 'CustomViews/informer_image.html.twig']);

    }

    public function prePersist($informer)
    {
        $informer->setGuid(Tools::generateGUID('INF', 8));
    }

}
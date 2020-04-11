<?php

namespace App\Admin;

use App\Manager\FileManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
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
     *
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

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('phoneNumber')
            ->add('culpableFirstName')
            ->add('culpableLastName')
            ->add('culpableAddress');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('phoneNumber')
            ->add('culpableFirstName')
            ->add('culpableLastName')
            ->add('culpableAddress')
            ->add('_action', 'actions', ['actions' => ['show' => ['template' => ':CRUD:list__action_show.html.twig']]]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('guid')
            ->add('firstName')
            ->add('phoneNumber')
            ->add('zipCode')
            ->add('culpableFirstName')
            ->add('culpableLastName')
            ->add('culpableAddress')
            ->add('comment');

        // add image
        if ($this->image = $this->fm->getFile($this->getSubject())) {
            $this->image = sprintf('data:image/png;base64,%s', $this->image->getBase64EncodedString());
            $showMapper->add('image', null, ['template' => 'CustomViews/informer_image.html.twig']);
        }
    }
}
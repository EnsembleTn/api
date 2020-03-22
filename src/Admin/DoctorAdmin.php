<?php
declare(strict_types=1);

namespace App\Admin;

use App\Entity\Doctor;
use App\Manager\DoctorManager;
use App\Util\Tools;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 22‏/3‏/2020, Sun
 **/
final class DoctorAdmin extends AbstractAdmin
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encodingPassword;

    /**
     * DoctorAdmin constructor.
     * @param $code
     * @param $class
     * @param $baseControllerName
     * @param UserPasswordEncoderInterface $encodePassword
     */
    public function __construct($code, $class, $baseControllerName, UserPasswordEncoderInterface $encodePassword)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->encodingPassword = $encodePassword;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('import', 'import', [
            '_controller' => 'App\AdminController\UploadDoctor::indexAction'
        ]);

    }
    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();

        $actions['import'] = array(
            'label' => 'Import',
            'url' => $this->generateUrl('import'),
            'icon' => 'upload',
        );

        return $actions;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('guid')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('active')
            ->add('passwordRequestedAt')
            ->add('phoneNumber')
            ->add('roles');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('active')
        ->add('_action', 'actions', ['actions' => ['edit' => ['template' => ':CRUD:list__action_edit.html.twig'], 'delete' => ['template' => ':CRUD:list__action_delete.html.twig'], 'show' => ['template' => ':CRUD:list__action_show.html.twig']]]);


    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            //->add('guid')
            ->add('email')
            ->add('firstName')
            ->add('lastName');
        if (null !== $this->getSubject()) {
            $formMapper->add('plainPassword', TextType::class, [
                'required' => false,]);
        } else {
            $formMapper->add('plainPassword', TextType::class, [
                'required' => true,]);
        }

        $formMapper->add('active')
            //->add('passwordRequestedAt')
            ->add('phoneNumber')//->add('roles')
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('guid')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('password')
            ->add('active')
            ->add('passwordRequestedAt')
            ->add('phoneNumber');
    }

    public function prePersist($object)
    {
        if ($object instanceof Doctor != TRUE) {
            return false;
        } else {
            $plainPassword = $object->getPlainPassword();
            $object->setGuid(Tools::generateGUID('DCT', 12));
            if (!empty($plainPassword)) {
                $object->setPassword($this->encodingPassword->encodePassword($object, $plainPassword));
                $object->eraseCredentials();
            } else {
                return FALSE;
            }
        }
    }

    public function preUpdate($object)
    {
        if ($object instanceof Doctor != TRUE) {
            return false;
        } else {
            $plainPassword = $object->getPlainPassword();
            if (!empty($plainPassword)) {
                $object->setPassword($this->encodingPassword->encodePassword($object, $plainPassword));
                $object->eraseCredentials();
            } else {
                return true;
            }
        }
    }
}
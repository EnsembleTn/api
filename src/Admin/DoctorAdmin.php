<?php
declare(strict_types=1);

namespace App\Admin;

use App\Entity\Doctor;
use App\Util\Tools;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class DoctorAdmin
 *
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @author : Ghaith Daly <daly.ghaith@gmail.com>
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
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('region')
            ->add('category')
            ->add('active');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('region')
            ->add('category')
            ->add('active')
            ->add('_action', 'actions', ['actions' => ['edit' => ['template' => ':CRUD:list__action_edit.html.twig'], 'delete' => ['template' => ':CRUD:list__action_delete.html.twig'], 'show' => ['template' => ':CRUD:list__action_show.html.twig']]]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
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

        $formMapper
            ->add('phoneNumber')
            ->add('category', ChoiceType::class, [
                    'choices' => [
                        'CATEGORY_JUNIOR' => 'JUNIOR',
                        'CATEGORY_SENIOR' => 'SENIOR',
                    ],
                    'multiple' => false,
                    'required' => true,
                ]
            )
            ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'ROLE_DOCTOR' => 'ROLE_DOCTOR',
                        'ROLE_EMERGENCY_DOCTOR' => 'ROLE_EMERGENCY_DOCTOR',
                    ],
                    'multiple' => true,
                    'required' => true,
                ]
            )
            ->add('active');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('guid')
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('phoneNumber')
            ->add('region')
            ->add('category')
            ->add('active')
            ->add('rolesAsString', null, ['label' => 'Roles']);

    }

    public function prePersist($doctor)
    {
        $plainPassword = $doctor->getPlainPassword();
        $doctor
            ->setGuid(Tools::generateGUID('DCT', 8))
            ->setPassword($this->encodingPassword->encodePassword($doctor, $plainPassword))
            ->setRoles([Doctor::ROLE_DOCTOR])
            ->eraseCredentials();
    }

    public function preUpdate($doctor)
    {
        $plainPassword = $doctor->getPlainPassword();
        if (!empty($plainPassword)) {
            $doctor->setPassword($this->encodingPassword->encodePassword($doctor, $plainPassword));
            $doctor->eraseCredentials();
        } else {
            return true;
        }
    }
}
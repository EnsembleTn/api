<?php

namespace App\Admin;

use App\Manager\FileManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class PatientAdmin
 *
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @author : Ghaith Daly <daly.ghaith@gmail.com>
 **/
final class PatientAdmin extends AbstractAdmin
{
    /**
     * @var FileManager
     */
    private $fm;

    /**
     * @var string
     */
    public $audio = '';

    /**
     * PatientAdmin constructor.
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

    protected function configureFormFields(FormMapper $formMapper): void
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->add('denounced', ChoiceType::class, [
                    'choices' => [
                        'UNDENOUNCED' => 0,
                        'DENOUNCED' => 1,
                    ]
                ]);
        }
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('firstName', TextType::class);
        $showMapper->add('lastName', TextType::class);
        $showMapper->add('gender', TextType::class);
        $showMapper->add('address', TextType::class);
        $showMapper->add('city', TextType::class);
        $showMapper->add('zipCode');
        $showMapper->add('phoneNumber');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $showMapper->add('comment');
            $showMapper->add('status');
            $showMapper->add('emergencyStatus');
            $showMapper->add('flag');
            $showMapper->add('medicalStatus');
            $showMapper->add('isTestPositiveAsString', null, ['label' => 'Test Positive']);
            $showMapper->add('isDenouncedAsString', null, ['label' => 'Denounced']);
            $showMapper->add('doctor');
            $showMapper->add('doctorSms');
            $showMapper->add('emergencyDoctor');
            $showMapper->add('emergencyDoctorSms');
            $showMapper->add('createdAt');
            $showMapper->add('updatedAt');
        }

        // add audio
        if ($this->audio = $this->fm->getFile($this->getSubject())) {
            $this->audio = sprintf('data:audio/wav;base64,%s', $this->audio->getBase64EncodedString());
            $showMapper->add('audio', null, ['template' => 'CustomViews/patient_audio.html.twig']);
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('firstName');
        $datagridMapper->add('lastName');
        $datagridMapper->add('address');
        $datagridMapper->add('city');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $datagridMapper->add('status');
            $datagridMapper->add('emergencyStatus');
            $datagridMapper->add('flag');
        } else {
            $datagridMapper->add('phoneNumber');
            $datagridMapper->add('zipCode');
        }
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('firstName');
        $listMapper->add('lastName');
        $listMapper->add('address');
        $listMapper->add('city');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $listMapper->add('status');
            $listMapper->add('emergencyStatus');
            $listMapper->add('flag');
            $listMapper->add('isDenouncedAsString', null, ['label' => 'Denounced']);
        } else {
            $listMapper->add('phoneNumber');
            $listMapper->add('zipCode');
        }

        $listMapper->add('_action', 'actions', ['actions' => ['edit' => ['template' => ':CRUD:list__action_edit.html.twig'], 'delete' => ['template' => ':CRUD:list__action_delete.html.twig'], 'show' => ['template' => ':CRUD:list__action_show.html.twig']]]);

    }

    public function createQuery($context = 'list')
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return parent::createQuery($context);
        } else {
            $query = parent::createQuery($context);
            $query->andWhere(
                $query->expr()->in($query->getRootAlias() . '.denounced', 1));
            return $query;
        }
    }
}
<?php
/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 22‏/3‏/2020, Sun
 **/

namespace App\AdminController;

use App\Entity\Doctor;
use App\Manager\DoctorManager;
use App\Util\Tools;
use Doctrs\SonataImportBundle\Entity\UploadFile;
use Doctrs\SonataImportBundle\Form\Type\UploadFileType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sonata\AdminBundle\Controller\CRUDController as CRUDControllerAlias;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class UploadDoctor extends CRUDControllerAlias
{
    /**
     * @var DoctorManager
     */
    private $dm;

    /**
     * UploadDoctor constructor.
     * @param DoctorManager $dm
     */
    public function __construct(DoctorManager $dm)
    {
        $this->dm = $dm;
    }


    public function indexAction(Request $request) {
        $fileEntity = new UploadFile();
        $form = $this->createForm(UploadFileType::class, $fileEntity, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if (!$fileEntity->getFile()->getError()) {
                    $fileEntity->move($this->getParameter('doctrs_sonata_import.upload_dir'));
                    $data = [];
                    $this->getDoctrine()->getManager()->persist($fileEntity);
                    $this->getDoctrine()->getManager()->flush($fileEntity);
                    $filee = $this->getDoctrine()->getRepository('DoctrsSonataImportBundle:UploadFile')->find($fileEntity);
                    $extension = pathinfo($filee->getFile(), PATHINFO_EXTENSION);
                    $i = 0 ;
                    if ($extension === "txt") {
                        $this->getDoctrine()->getConnection()->getConfiguration()->setSQLLogger(null);
                        $data = Tools::csv_to_array($filee->getFile(), ',');
                        foreach ($data as $key=>$value)
                        {

                            $doctor  = new Doctor();
                            $name = Tools::split_name($value['Nom et Prénom']);
                            $doctor
                                ->setEmail($value['email'])
                                ->setPhoneNumber($value['Numèro de tèlèphone '])
                                ->setRegion($value['Région'])
                                ->setFirstName($name['firstName'])
                                ->setLastName($name['lastName'])
                                ->setActive(true)
                                ->setPlainPassword(Tools::generateRandomPassword());
                            if($value['Catégorie'] == 'Junior')
                            {
                                $doctor->setCategory(1);
                            }else $doctor->setCategory(2);
                        }

                        $this->dm->registerDoctor($doctor);

                    } else
                    {
                        $form->get('file')->addError(new FormError('Unsupported extension '));
                    }

                } else {
                    $form->get('file')->addError(new FormError($fileEntity->getFile()->getErrorMessage()));
                }
            }
        }



        $builder = $this->get('sonata.admin.pool')
            ->getInstance($this->admin->getCode())
            ->getExportFields()
        ;
        return $this->render('CustomViews/import_doctors.html.twig', [
            'form' => $form->createView(),
            'baseTemplate' => $this->getBaseTemplate(),
            'builder' => $builder,
            'action' => 'import'
        ]);
    }


}
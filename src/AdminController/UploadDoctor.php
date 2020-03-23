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
use Sonata\AdminBundle\Controller\CRUDController as CRUDControllerAlias;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UploadDoctor extends CRUDControllerAlias
{
    /**
     * @var DoctorManager
     */
    private $dm;

    /**
     * @var ValidatorInterface
     */
    private $vi;

    /**
     * UploadDoctor constructor.
     * @param DoctorManager $dm
     * @param ValidatorInterface $vi
     */
    public function __construct(DoctorManager $dm, ValidatorInterface $vi)
    {
        $this->dm = $dm;
        $this->vi = $vi;
    }


    public function indexAction(Request $request) {
        $fileEntity = new UploadFile();
        $form = $this->createForm(UploadFileType::class, $fileEntity, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $fileEntity->getFile();
            if (!$file->getError()) {
                $extension = $file->guessExtension();
                if (in_array($extension, ['txt'])) {
                    $this->getDoctrine()->getConnection()->getConfiguration()->setSQLLogger(null);
                    $rows = Tools::csv_to_array($file, ',');
                    $emailConstraint = new EmailConstraint();
                    foreach ($rows as $row)
                    {
                        $doctor  = new Doctor();
                        $name = Tools::split_name($row['Nom et Prénom']);
                        $email = $row['Email'];
                        $emailValidationErrors = $this->vi->validate($email, $emailConstraint);
                        if (count($emailValidationErrors) == 0) {
                            $doctor
                                ->setEmail($row['Email'])
                                ->setPhoneNumber($row['Numèro de tèlèphone'])
                                ->setRegion($row['Région'])
                                ->setFirstName($name['firstName'])
                                ->setLastName($name['lastName'])
                                ->setActive(true);
                            $catg = $row['Catégorie'];
                            if(strpos(strtolower($catg), 'junior') !== false)
                            {
                                $doctor->setCategory(Doctor::CATEGORY_JUNIOR);
                            } else {
                                $doctor->setCategory(Doctor::CATEGORY_SENIOR);
                            }

                            $this->dm->registerDoctor($doctor, true, true);
                        }

                    }

                    $this->getDoctrine()->getManager()->flush();
                    $this->getDoctrine()->getManager()->clear();

                } else {
                    $form->get('file')->addError(new FormError('Unsupported extension '));
                }

            } else {
                $form->get('file')->addError(new FormError($file->getErrorMessage()));
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
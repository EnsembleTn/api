<?php

namespace App\AdminController;

use App\Entity\Doctor;
use App\Manager\DoctorManager;
use App\Util\Tools;
use Doctrs\SonataImportBundle\Entity\UploadFile;
use Doctrs\SonataImportBundle\Form\Type\UploadFileType;
use Exception;
use Sonata\AdminBundle\Controller\CRUDController as CRUDControllerAlias;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UploadDoctor
 *
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @author : Ghaith Daly <daly.ghaith@gmail.com>
 **/
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

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function indexAction(Request $request)
    {
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

                    // convert the csv file to array
                    $rows = Tools::csv_to_array($file, ',');

                    if (Tools::all_array_keys_exists(
                        ['Nom et Prénom', 'Email', 'Numéro de téléphone', 'Région', 'Catégorie'],
                        array_flip(Tools::array_second_level_keys($rows))
                    )) {

                        foreach ($rows as $row) {

                            // split fullname
                            $name = Tools::split_name($row['Nom et Prénom']);

                            // validate the $email;
                            $email = $row['Email'];
                            $emailValidationErrors = $this->vi->validate($email, new EmailConstraint());

                            if (count($emailValidationErrors) == 0) {

                                // add doctor if not exists
                                if (!$this->dm->getDoctorByEmail($email)) {
                                    //create doctor object and fill data
                                    $doctor = (new Doctor())
                                        ->setEmail($row['Email'])
                                        ->setPhoneNumber($row['Numéro de téléphone'])
                                        ->setRegion(ucfirst($row['Région']))
                                        ->setFirstName(ucfirst($name['firstName']))
                                        ->setLastName(ucfirst($name['lastName']))
                                        ->addRole('ROLE_DOCTOR')
                                        ->setActive(true);

                                    //handle doctor category
                                    $category = $row['Catégorie'];
                                    if (strpos(strtolower($category), 'junior') !== false) {
                                        $doctor->setCategory(Doctor::CATEGORY_JUNIOR);
                                    } else {
                                        $doctor->setCategory(Doctor::CATEGORY_SENIOR);
                                    }

                                    $this->dm->registerDoctor($doctor);
                                }
                            }

                        }

                        $this->getDoctrine()->getManager()->flush();
                        $this->getDoctrine()->getManager()->clear();

                    } else {
                        $form->get('file')->addError(new FormError('Wrong/Missing columns'));
                    }

                } else {
                    $form->get('file')->addError(new FormError('Unsupported extension'));
                }

            } else {
                $form->get('file')->addError(new FormError($file->getErrorMessage()));
            }
        }

        $builder = $this->get('sonata.admin.pool')->getInstance($this->admin->getCode())->getExportFields();

        return $this->renderWithExtraParams('CustomViews/import_doctors.html.twig', [
            'form' => $form->createView(),
            'baseTemplate' => $this->getBaseTemplate(),
            'builder' => $builder,
            'action' => 'import'
        ]);
    }


}
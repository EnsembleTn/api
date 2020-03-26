<?php

namespace App\Command;

use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RevertPatients
 *
 * This script is used by a cron to revert all patient for inactivity
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class RevertPatients extends Command
{
    const INACTIVITY_TIME = 900; // 15 minutes

    protected static $defaultName = 'app:revert-patients';

    /**
     * @var int
     */
    private $counter = 0;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CreateUserCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }


    protected function configure()
    {
        $this
            ->setName('app:revert-patients')
            ->setDescription('Revert patients due to inactivity');

        // this cron should be ran every 30 minutes :   */30 * * * * php /path/to/app/bin/console app:revert-patients
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('------------ Reverting inactive patients Begin ------------');

        // In progress patients for doctors
        $batch1 = $this->entityManager->getRepository(Patient::class)->findBy(['status' => Patient::STATUS_IN_PROGRESS]);

        // In progress patients for emergency doctors
        $batch2 = $this->entityManager->getRepository(Patient::class)->findBy(['emergencyStatus' => Patient::STATUS_IN_PROGRESS]);

        $patients = array_merge($batch1, $batch2);


        foreach ($patients as $patient) {
            if ($patient->getUpdatedAt()->getTimestamp() + self::INACTIVITY_TIME < time()) {

                $setStatusMethod = $patient->getFlag() ? 'setEmergencyStatus' : 'setStatus';

                call_user_func([$patient, $setStatusMethod], Patient::STATUS_ON_HOLD);

                $this->entityManager->flush();

                $this->counter++;
            }
        }

        $output->writeln("{$this->counter} patients reverted");
        $output->writeln('------------- Reverting inactive patients End -------------');
        $output->writeln('---------------- We will defeat Covid19 :) ----------------');
    }
}
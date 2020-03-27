<?php

namespace App\Manager;

use App\Entity\Informer;
use App\Util\Tools;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class InformerManager
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class InformerManager
{
    /**
     * TTL for submitting cases
     */
    const RETRY_TTL = 21600; // 6 hours

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * InformerManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    /**
     * Save informer
     *
     * @param Informer $informer
     * @throws Exception
     */
    public function save(Informer $informer): void
    {
        // generate GUID
        $informer->setGuid(Tools::generateGUID('INF', 8));


        $this->em->persist($informer);
        $this->em->flush();
    }

    /**
     * Load informers by phoneNumber
     *
     * @param int $phoneNumber
     * @param string $orderBy
     * @return object[]|null
     */
    public function getByPhoneNUmber(int $phoneNumber, $orderBy = 'ASC')
    {
        return $this->em->getRepository(Informer::class)->findBy([
            'phoneNumber' => $phoneNumber
        ], ['createdAt' => 'DESC']);
    }

    /**
     * @param Informer $informer
     * @return string|null
     */
    public function canSubmit(Informer $informer): ?string
    {
        if (!$informers = $this->getByPhoneNUmber($informer->getPhoneNumber(), 'DESC'))
            return null;

        $reversedInformersArray = array_reverse($informers);

        if (($lastDenunciation = array_pop($reversedInformersArray))->getCreatedAt()->getTimestamp() + self::RETRY_TTL > time()) {

            return date('H:i:s', $lastDenunciation->getCreatedAt()->getTimestamp() + self::RETRY_TTL - (time() + 3600)); // adding 3600s to fix timezone;
        }

        return null;
    }
}

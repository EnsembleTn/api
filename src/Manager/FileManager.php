<?php

namespace App\Manager;

use App\Entity\File;
use App\Globals;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class FileManager
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class FileManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * FileManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     */
    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * create new File entry
     *
     * @param mixed $object
     * @param string $base64EncodedString
     * @param string $path
     */
    public function newFile(object $object, string $base64EncodedString, string $path = null): void
    {
        $file = new File();
        $file
            ->setObjectId($object->getId())
            ->setEntity(get_class($object))
            ->setBase64EncodedString($base64EncodedString);

        if ($path) {
            $file
                ->setFileName(basename($path))
                ->setMimeType(pathinfo($path, PATHINFO_EXTENSION))
                ->setSize(filesize($path))
                ->setFilePath($object->getUploadPath() . basename($path));
        }

        $this->em->persist($file);
        $this->em->flush();
    }

    /**
     * Get file by guid
     *
     * @param string $guid
     * @return File|object|null
     */
    public function getFileByGuid(string $guid)
    {
        return $this->em->getRepository(File::class)->findOneBy(['guid' => $guid]);
    }

    /**
     * Get file by type
     *
     * @param mixed $object
     * @param int $type
     * @return File|object|null
     */
    public function getFileByType(object $object, int $type)
    {
        return $this->em->getRepository(File::class)->findOneBy([
            'entity' => get_class($object),
            'objectId' => $object->getId(),
            'type' => $type
        ]);
    }

    /**
     * Delete file by guid
     *
     * @param $guid
     */
    public function deleteFile(string $guid): void
    {
        if (!$file = $this->getFileByGuid($guid)) {
            return;
        }

        $this->em->remove($file);
        $this->em->flush();
    }

    public function getLink(?File $file): ?string
    {
        if ($file) {
            return sprintf(
                '%s%s%s',
                $this->getApiBaseUrl(),
                Globals::UPLOADS_PATH,
                $file->getFilePath()
            );
        }

        return null;
    }

    /**
     * Get Api Base URL
     *
     * @return string
     */
    public function getApiBaseUrl(): string
    {
        $scheme = $this->router->getContext()->getScheme();
        $port = $this->router->getContext()->getHttpPort();
        $host = $this->router->getContext()->getHost();

        return sprintf('%s://%s:%s', $scheme, $host, $port);
    }
}

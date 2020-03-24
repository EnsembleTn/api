<?php

namespace App\EventListener;

use App\Entity\Interfaces\Uploadable;
use App\Event\FileUploadEvent;
use App\Manager\FileManager;
use App\Service\Base64UploaderInterface;

/**
 * Handling file upload for uploadable objects
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class FileUploadListener
{

    /**
     * @var Base64UploaderInterface
     */
    private $uploader;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * MultipleFileUploadListener constructor.
     *
     * @param Base64UploaderInterface $uploader
     * @param FileManager $fileManager
     */
    public function __construct(Base64UploaderInterface $uploader, FileManager $fileManager)
    {
        $this->uploader = $uploader;
        $this->fileManager = $fileManager;
    }

    /**
     * Handling file upload for a specific object
     * @param FileUploadEvent $event
     */
    public function onUploadableObjectCall(FileUploadEvent $event)
    {
        if (!($entity = $event->getObject()) instanceof Uploadable) {
            return;
        }

        $path = null;

        if ($event->isUploadableToServer())
            $path = $this->uploader->upload($event->getBase64EncodedFile(), $entity->getUploadPath());

        $this->fileManager->newFile($entity, $event->getBase64EncodedFile(), $path);

    }
}
